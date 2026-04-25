<?php

use App\Filament\Resources\FinalProducts\Pages\CreateFinalProduct;
use App\Filament\Resources\FinalProducts\Pages\EditFinalProduct;
use App\Filament\Resources\FinalProducts\Pages\ListFinalProducts;
use App\Models\Category;
use App\Models\FinalProduct;
use App\Models\User;

use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Role;

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    $this->actingAs($user);
});

it('zeigt die Finale-Produkte-Liste an', function () {
    FinalProduct::factory()->count(3)->create();

    livewire(ListFinalProducts::class)->assertSuccessful();
});

it('legt ein FinalProduct mit SKU und Preisen an', function () {
    $category = Category::factory()->create();

    livewire(CreateFinalProduct::class)
        ->fillForm([
            'name' => 'Pro Summer Jersey v1',
            'category_id' => $category->id,
            'sku' => 'STZ-JRY-PSJ-001',
            'cost_price' => 25.00,
            'retail_price' => 89.99,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $product = FinalProduct::where('sku', 'STZ-JRY-PSJ-001')->first();
    expect($product)->not->toBeNull();
    expect((float) $product->retail_price)->toBe(89.99);
});

it('öffnet die Edit-Seite für ein FinalProduct', function () {
    $product = FinalProduct::factory()->create();

    livewire(EditFinalProduct::class, ['record' => $product->getRouteKey()])
        ->assertSuccessful();
});

it('akzeptiert Nachhaltigkeits-Felder', function () {
    $category = Category::factory()->create();

    livewire(CreateFinalProduct::class)
        ->fillForm([
            'name' => 'Nachhaltig',
            'category_id' => $category->id,
            'sku' => 'STZ-ECO-001',
            'co2_kg' => 4.5,
            'recycled_content_pct' => 95,
            'certifications' => ['bluesign', 'gots'],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $product = FinalProduct::where('sku', 'STZ-ECO-001')->first();
    expect((float) $product->co2_kg)->toBe(4.5);
    expect($product->recycled_content_pct)->toBe(95);
    expect($product->certifications)->toBe(['bluesign', 'gots']);
});

it('FinalProduct nutzt das Trait HasRatings', function () {
    $product = FinalProduct::factory()->create();

    expect(method_exists($product, 'ratings'))->toBeTrue();
    expect(method_exists($product, 'averageScore'))->toBeTrue();
    expect($product->averageScore())->toBeNull();
});

it('FinalProduct nutzt das Trait HasQualityChecks', function () {
    $product = FinalProduct::factory()->create();

    expect(method_exists($product, 'qualityChecks'))->toBeTrue();
    expect(method_exists($product, 'qualityScore'))->toBeTrue();
});
