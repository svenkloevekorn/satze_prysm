<?php

use App\Filament\Resources\CompetitorProducts\Pages\ListCompetitorProducts;
use App\Filament\Resources\SupplierProducts\Pages\ListSupplierProducts;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CompetitorProduct;
use App\Models\Supplier;
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

it('setzt die Marke per Bulk-Action auf mehreren Wettbewerbsprodukten', function () {
    $alteMarke = Brand::factory()->create();
    $neueMarke = Brand::factory()->create();
    $produkte = CompetitorProduct::factory()->count(3)->create(['brand_id' => $alteMarke->id]);

    livewire(ListCompetitorProducts::class)
        ->callTableBulkAction('changeBrand', $produkte, data: ['brand_id' => $neueMarke->id]);

    foreach ($produkte as $p) {
        expect($p->fresh()->brand_id)->toBe($neueMarke->id);
    }
});

it('ändert den Preis-Range per Bulk-Action', function () {
    $produkte = CompetitorProduct::factory()->count(2)->create([
        'price_min' => 10,
        'price_max' => 20,
    ]);

    livewire(ListCompetitorProducts::class)
        ->callTableBulkAction('changePrice', $produkte, data: ['price_min' => 50, 'price_max' => 99]);

    foreach ($produkte as $p) {
        expect((float) $p->fresh()->price_min)->toBe(50.0);
        expect((float) $p->fresh()->price_max)->toBe(99.0);
    }
});

it('ändert den Lieferanten per Bulk-Action auf mehreren Lieferanten-Produkten', function () {
    $alterLieferant = Supplier::factory()->create();
    $neuerLieferant = Supplier::factory()->create();
    $produkte = SupplierProduct::factory()->count(2)->create(['supplier_id' => $alterLieferant->id]);

    livewire(ListSupplierProducts::class)
        ->callTableBulkAction('changeSupplier', $produkte, data: ['supplier_id' => $neuerLieferant->id]);

    foreach ($produkte as $p) {
        expect($p->fresh()->supplier_id)->toBe($neuerLieferant->id);
    }
});

it('ändert den Einkaufspreis per Bulk-Action', function () {
    $produkte = SupplierProduct::factory()->count(3)->create(['purchase_price' => 5.0]);

    livewire(ListSupplierProducts::class)
        ->callTableBulkAction('changePurchasePrice', $produkte, data: ['purchase_price' => 12.50]);

    foreach ($produkte as $p) {
        expect((float) $p->fresh()->purchase_price)->toBe(12.50);
    }
});

it('setzt die MOQ per Bulk-Action', function () {
    $produkte = SupplierProduct::factory()->count(2)->create(['moq' => 50]);

    livewire(ListSupplierProducts::class)
        ->callTableBulkAction('changeMoq', $produkte, data: ['moq' => 200]);

    foreach ($produkte as $p) {
        expect($p->fresh()->moq)->toBe(200);
    }
});

it('setzt die Kategorie per Bulk-Action auf Wettbewerbsprodukten', function () {
    $alt = Category::factory()->create();
    $neu = Category::factory()->create();
    $produkte = CompetitorProduct::factory()->count(2)->create(['category_id' => $alt->id]);

    livewire(ListCompetitorProducts::class)
        ->callTableBulkAction('changeCategory', $produkte, data: ['category_id' => $neu->id]);

    foreach ($produkte as $p) {
        expect($p->fresh()->category_id)->toBe($neu->id);
    }
});
