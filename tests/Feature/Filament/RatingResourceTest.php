<?php

use App\Enums\RatingType;
use App\Filament\Resources\CompetitorProducts\Pages\EditCompetitorProduct;
use App\Filament\Resources\CompetitorProducts\RelationManagers\RatingsRelationManager as CompetitorRatingsRM;
use App\Filament\Resources\Ratings\Pages\CreateRating;
use App\Filament\Resources\Ratings\Pages\ListRatings;
use App\Filament\Resources\SupplierProducts\Pages\EditSupplierProduct;
use App\Filament\Resources\SupplierProducts\RelationManagers\RatingsRelationManager as SupplierRatingsRM;
use App\Models\CompetitorProduct;
use App\Models\Rating;
use App\Models\RatingDimension;
use App\Models\SupplierProduct;
use App\Models\User;

use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Role;

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    $this->actingAs($user);
});

it('zeigt die Bewertungs-Liste an', function () {
    Rating::factory()->count(3)->create();
    livewire(ListRatings::class)->assertSuccessful();
});

it('legt eine Bewertung über die eigenständige Resource an', function () {
    $product = CompetitorProduct::factory()->create();

    livewire(CreateRating::class)
        ->fillForm([
            'ratable_type' => 'competitor_product',
            'ratable_id' => $product->id,
            'type' => RatingType::Internal->value,
            'score' => 8,
            'comment' => 'Test Kommentar',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Rating::where('ratable_id', $product->id)->where('score', 8)->exists())->toBeTrue();
});

it('verlangt einen Score zwischen 1 und 10', function () {
    $product = CompetitorProduct::factory()->create();

    livewire(CreateRating::class)
        ->fillForm([
            'ratable_type' => 'competitor_product',
            'ratable_id' => $product->id,
            'score' => 11,
        ])
        ->call('create')
        ->assertHasFormErrors(['score']);
});

it('verknuepft Bewertungen polymorph mit Wettbewerbsprodukt', function () {
    $product = CompetitorProduct::factory()->create();
    Rating::factory()->count(3)->create([
        'ratable_type' => 'competitor_product',
        'ratable_id' => $product->id,
    ]);

    expect($product->ratings)->toHaveCount(3);
    expect($product->ratings->first()->ratable->is($product))->toBeTrue();
});

it('verknuepft Bewertungen polymorph mit Lieferanten-Produkt', function () {
    $product = SupplierProduct::factory()->create();
    Rating::factory()->count(2)->create([
        'ratable_type' => 'supplier_product',
        'ratable_id' => $product->id,
    ]);

    expect($product->ratings)->toHaveCount(2);
});

it('berechnet den Durchschnitts-Score', function () {
    $product = CompetitorProduct::factory()->create();
    Rating::factory()->create(['ratable_type' => 'competitor_product', 'ratable_id' => $product->id, 'score' => 6]);
    Rating::factory()->create(['ratable_type' => 'competitor_product', 'ratable_id' => $product->id, 'score' => 10]);

    expect($product->averageScore())->toBe(8.0);
});

it('zeigt Bewertungen im CompetitorProduct-RelationManager', function () {
    $product = CompetitorProduct::factory()->create();
    Rating::factory()->count(2)->create([
        'ratable_type' => 'competitor_product',
        'ratable_id' => $product->id,
    ]);

    livewire(CompetitorRatingsRM::class, [
        'ownerRecord' => $product,
        'pageClass' => EditCompetitorProduct::class,
    ])->assertSuccessful();
});

it('zeigt Bewertungen im SupplierProduct-RelationManager', function () {
    $product = SupplierProduct::factory()->create();
    Rating::factory()->count(2)->create([
        'ratable_type' => 'supplier_product',
        'ratable_id' => $product->id,
    ]);

    livewire(SupplierRatingsRM::class, [
        'ownerRecord' => $product,
        'pageClass' => EditSupplierProduct::class,
    ])->assertSuccessful();
});

it('unterscheidet zwischen internen und externen Bewertungen', function () {
    $product = CompetitorProduct::factory()->create();

    Rating::factory()->create(['ratable_type' => 'competitor_product', 'ratable_id' => $product->id, 'type' => 'internal']);
    Rating::factory()->create(['ratable_type' => 'competitor_product', 'ratable_id' => $product->id, 'type' => 'external']);

    expect($product->ratings()->where('type', 'internal')->count())->toBe(1);
    expect($product->ratings()->where('type', 'external')->count())->toBe(1);
});

it('erlaubt Bewertung mit oder ohne Dimension', function () {
    $product = CompetitorProduct::factory()->create();
    $dim = RatingDimension::factory()->create();

    Rating::factory()->create(['ratable_type' => 'competitor_product', 'ratable_id' => $product->id, 'rating_dimension_id' => null]);
    Rating::factory()->create(['ratable_type' => 'competitor_product', 'ratable_id' => $product->id, 'rating_dimension_id' => $dim->id]);

    expect($product->ratings)->toHaveCount(2);
    expect($product->ratings->whereNull('rating_dimension_id'))->toHaveCount(1);
});
