<?php

use App\Enums\QualityCheckStatus;
use App\Filament\Resources\CompetitorProducts\Pages\EditCompetitorProduct;
use App\Filament\Resources\CompetitorProducts\RelationManagers\QualityChecksRelationManager;
use App\Models\Category;
use App\Models\CompetitorProduct;
use App\Models\QualityCheck;
use App\Models\QualityCriterion;
use App\Models\User;

use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Role;

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $this->user = User::factory()->create();
    $this->user->assignRole($role);
    $this->actingAs($this->user);
});

it('hat eine polymorphe qualityChecks-Relation', function () {
    $product = CompetitorProduct::factory()->create();

    QualityCheck::create([
        'checkable_type' => $product->getMorphClass(),
        'checkable_id' => $product->id,
        'quality_criterion_id' => QualityCriterion::factory()->create()->id,
        'user_id' => $this->user->id,
        'status' => QualityCheckStatus::Pending->value,
    ]);

    expect($product->qualityChecks)->toHaveCount(1);
    expect($product->qualityChecks->first()->checkable->is($product))->toBeTrue();
});

it('berechnet den qualityScore aus Pass und Fail', function () {
    $product = CompetitorProduct::factory()->create();

    foreach ([
        QualityCheckStatus::Pass,
        QualityCheckStatus::Pass,
        QualityCheckStatus::Pass,
        QualityCheckStatus::Pass,
        QualityCheckStatus::Fail,
    ] as $status) {
        QualityCheck::create([
            'checkable_type' => $product->getMorphClass(),
            'checkable_id' => $product->id,
            'quality_criterion_id' => QualityCriterion::factory()->create()->id,
            'user_id' => $this->user->id,
            'status' => $status->value,
        ]);
    }

    expect($product->qualityScore())->toBe(80);
});

it('ignoriert Pending und NotApplicable im qualityScore', function () {
    $product = CompetitorProduct::factory()->create();

    foreach ([
        QualityCheckStatus::Pass,
        QualityCheckStatus::Pending,
        QualityCheckStatus::NotApplicable,
    ] as $status) {
        QualityCheck::create([
            'checkable_type' => $product->getMorphClass(),
            'checkable_id' => $product->id,
            'quality_criterion_id' => QualityCriterion::factory()->create()->id,
            'user_id' => $this->user->id,
            'status' => $status->value,
        ]);
    }

    // 1 Pass / (1 Pass + 0 Fail) = 100
    expect($product->qualityScore())->toBe(100);
});

it('liefert null beim qualityScore wenn keine Pass/Fail vorhanden sind', function () {
    $product = CompetitorProduct::factory()->create();

    expect($product->qualityScore())->toBeNull();

    QualityCheck::create([
        'checkable_type' => $product->getMorphClass(),
        'checkable_id' => $product->id,
        'quality_criterion_id' => QualityCriterion::factory()->create()->id,
        'user_id' => $this->user->id,
        'status' => QualityCheckStatus::Pending->value,
    ]);

    expect($product->fresh()->qualityScore())->toBeNull();
});

it('rendert den QualityChecks-RelationManager', function () {
    $product = CompetitorProduct::factory()->create();

    livewire(QualityChecksRelationManager::class, [
        'ownerRecord' => $product,
        'pageClass' => EditCompetitorProduct::class,
    ])->assertSuccessful();
});

it('füllt die Checkliste aus den Kategorie-Kriterien automatisch', function () {
    $category = Category::factory()->create();
    $criteria = QualityCriterion::factory()->count(3)->create(['is_active' => true]);
    $criteria->each(fn ($c) => $c->categories()->attach($category->id));

    $product = CompetitorProduct::factory()->create(['category_id' => $category->id]);

    livewire(QualityChecksRelationManager::class, [
        'ownerRecord' => $product,
        'pageClass' => EditCompetitorProduct::class,
    ])->callTableAction('initFromCategory');

    expect($product->fresh()->qualityChecks)->toHaveCount(3);
    expect($product->fresh()->qualityChecks->pluck('status')->unique()->all())
        ->toBe([QualityCheckStatus::Pending]);
});

it('legt keine Duplikate beim erneuten initFromCategory an', function () {
    $category = Category::factory()->create();
    $criterion = QualityCriterion::factory()->create(['is_active' => true]);
    $criterion->categories()->attach($category->id);

    $product = CompetitorProduct::factory()->create(['category_id' => $category->id]);

    $component = livewire(QualityChecksRelationManager::class, [
        'ownerRecord' => $product,
        'pageClass' => EditCompetitorProduct::class,
    ]);

    $component->callTableAction('initFromCategory');
    $component->callTableAction('initFromCategory');

    expect($product->fresh()->qualityChecks)->toHaveCount(1);
});

it('gibt eine Fehler-Notification wenn Objekt keine Kategorie hat', function () {
    $product = CompetitorProduct::factory()->create(['category_id' => null]);

    livewire(QualityChecksRelationManager::class, [
        'ownerRecord' => $product,
        'pageClass' => EditCompetitorProduct::class,
    ])->callTableAction('initFromCategory');

    expect($product->fresh()->qualityChecks)->toHaveCount(0);
});
