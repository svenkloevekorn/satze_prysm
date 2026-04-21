<?php

use App\Filament\Resources\SupplierProducts\Pages\CreateSupplierProduct;
use App\Filament\Resources\SupplierProducts\Pages\ListSupplierProducts;
use App\Filament\Resources\Suppliers\Pages\CreateSupplier;
use App\Filament\Resources\Suppliers\Pages\EditSupplier;
use App\Filament\Resources\Suppliers\Pages\ListSuppliers;
use App\Filament\Resources\Suppliers\RelationManagers\ContactsRelationManager;
use App\Filament\Resources\Suppliers\RelationManagers\ProductsRelationManager;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\SupplierContact;
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

it('zeigt die Lieferanten-Liste an', function () {
    Supplier::factory()->count(3)->create();
    livewire(ListSuppliers::class)->assertSuccessful();
});

it('legt einen neuen Lieferanten an', function () {
    livewire(CreateSupplier::class)
        ->fillForm([
            'name' => 'Test Supplier Ltd',
            'country' => 'CN',
            'rating' => 8,
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Supplier::where('name', 'Test Supplier Ltd')->exists())->toBeTrue();
});

it('verlangt einen Firmennamen', function () {
    livewire(CreateSupplier::class)
        ->fillForm(['name' => ''])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

it('validiert Bewertung (1-10)', function () {
    livewire(CreateSupplier::class)
        ->fillForm([
            'name' => 'Bad Rating',
            'rating' => 11,
        ])
        ->call('create')
        ->assertHasFormErrors(['rating']);
});

it('hat Kontakte via Relation', function () {
    $supplier = Supplier::factory()->create();
    SupplierContact::factory()->count(2)->create(['supplier_id' => $supplier->id]);

    expect($supplier->contacts)->toHaveCount(2);
});

it('zeigt Ansprechpartner im RelationManager', function () {
    $supplier = Supplier::factory()->create();
    SupplierContact::factory()->count(2)->create(['supplier_id' => $supplier->id]);

    livewire(ContactsRelationManager::class, [
        'ownerRecord' => $supplier,
        'pageClass' => EditSupplier::class,
    ])->assertSuccessful();
});

it('zeigt Lieferanten-Produkte im RelationManager', function () {
    $supplier = Supplier::factory()->create();
    SupplierProduct::factory()->count(2)->create(['supplier_id' => $supplier->id]);

    livewire(ProductsRelationManager::class, [
        'ownerRecord' => $supplier,
        'pageClass' => EditSupplier::class,
    ])->assertSuccessful();
});

it('zeigt Lieferanten-Produkt-Liste an', function () {
    SupplierProduct::factory()->count(3)->create();
    livewire(ListSupplierProducts::class)->assertSuccessful();
});

it('legt ein neues Lieferanten-Produkt an', function () {
    $supplier = Supplier::factory()->create();
    $category = Category::factory()->create();

    livewire(CreateSupplierProduct::class)
        ->fillForm([
            'name' => 'Test Produkt',
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'purchase_price' => 15.50,
            'recommended_retail_price' => 49.00,
            'moq' => 100,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(SupplierProduct::where('name', 'Test Produkt')->exists())->toBeTrue();
});

it('loescht Kontakte mit dem Lieferanten (cascade)', function () {
    $supplier = Supplier::factory()->create();
    SupplierContact::factory()->count(2)->create(['supplier_id' => $supplier->id]);

    expect(SupplierContact::count())->toBe(2);

    $supplier->delete();

    expect(SupplierContact::count())->toBe(0);
});
