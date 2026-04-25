<?php

use App\Filament\Resources\CompetitorProducts\Pages\CreateCompetitorProduct;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CompetitorProduct;
use App\Models\User;

use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Role;

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    $this->actingAs($user);
});

it('akzeptiert nur Preise >= 0', function () {
    $brand = Brand::factory()->create();
    $category = Category::factory()->create();

    livewire(CreateCompetitorProduct::class)
        ->fillForm([
            'name' => 'Negativ',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'price_min' => -10,
        ])
        ->call('create')
        ->assertHasFormErrors(['price_min']);
});

it('speichert leere Materialien-Liste als null oder leeres Array', function () {
    $brand = Brand::factory()->create();
    $category = Category::factory()->create();

    livewire(CreateCompetitorProduct::class)
        ->fillForm([
            'name' => 'Ohne Materialien',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $product = CompetitorProduct::where('name', 'Ohne Materialien')->first();
    expect($product->materials)->toBeIn([null, []]);
});

it('akzeptiert recycled_content_pct nur zwischen 0 und 100', function () {
    $brand = Brand::factory()->create();
    $category = Category::factory()->create();

    livewire(CreateCompetitorProduct::class)
        ->fillForm([
            'name' => 'Out-of-range',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'recycled_content_pct' => 150,
        ])
        ->call('create')
        ->assertHasFormErrors(['recycled_content_pct']);
});
