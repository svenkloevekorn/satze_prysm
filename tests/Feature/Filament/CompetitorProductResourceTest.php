<?php

use App\Filament\Resources\CompetitorProducts\Pages\CreateCompetitorProduct;
use App\Filament\Resources\CompetitorProducts\Pages\EditCompetitorProduct;
use App\Filament\Resources\CompetitorProducts\Pages\ListCompetitorProducts;
use App\Filament\Resources\CompetitorProducts\RelationManagers\ShopEntriesRelationManager;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CompetitorProduct;
use App\Models\ProductShopEntry;
use App\Models\Shop;
use App\Models\User;
use Spatie\Permission\Models\Role;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    $this->actingAs($user);
});

it('zeigt die Wettbewerbsprodukt-Liste an', function () {
    CompetitorProduct::factory()->count(3)->create();
    livewire(ListCompetitorProducts::class)->assertSuccessful();
});

it('legt ein neues Wettbewerbsprodukt an', function () {
    $brand = Brand::factory()->create();
    $category = Category::factory()->create();

    livewire(CreateCompetitorProduct::class)
        ->fillForm([
            'name' => 'Test Trikot',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'price_min' => 49.99,
            'price_max' => 79.99,
            'overall_rating' => 8,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(CompetitorProduct::where('name', 'Test Trikot')->exists())->toBeTrue();
});

it('verlangt einen Namen', function () {
    livewire(CreateCompetitorProduct::class)
        ->fillForm(['name' => ''])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

it('verlangt eine Kategorie', function () {
    livewire(CreateCompetitorProduct::class)
        ->fillForm(['name' => 'Foo', 'category_id' => null])
        ->call('create')
        ->assertHasFormErrors(['category_id' => 'required']);
});

it('speichert Materialien als Array', function () {
    $brand = Brand::factory()->create();
    $category = Category::factory()->create();

    livewire(CreateCompetitorProduct::class)
        ->fillForm([
            'name' => 'Mit Materialien',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'materials' => ['Polyester', 'Nylon'],
            'colors' => ['Schwarz'],
            'sizes' => ['M', 'L'],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $product = CompetitorProduct::where('name', 'Mit Materialien')->first();
    expect($product->materials)->toBe(['Polyester', 'Nylon']);
    expect($product->colors)->toBe(['Schwarz']);
    expect($product->sizes)->toBe(['M', 'L']);
});

it('verknuepft Shop-Eintraege mit Produkten', function () {
    $product = CompetitorProduct::factory()->create();
    $shop = Shop::factory()->create();

    ProductShopEntry::factory()->create([
        'competitor_product_id' => $product->id,
        'shop_id' => $shop->id,
        'observed_price' => 99.99,
    ]);

    expect($product->shopEntries)->toHaveCount(1);
    expect($product->shopEntries->first()->shop->id)->toBe($shop->id);
});

it('zeigt Shop-Eintraege im Relation-Manager', function () {
    $product = CompetitorProduct::factory()->create();
    ProductShopEntry::factory()->count(2)->create(['competitor_product_id' => $product->id]);

    livewire(ShopEntriesRelationManager::class, [
        'ownerRecord' => $product,
        'pageClass' => EditCompetitorProduct::class,
    ])->assertSuccessful();
});
