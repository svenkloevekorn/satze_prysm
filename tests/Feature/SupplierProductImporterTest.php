<?php

use App\Filament\Imports\SupplierProductImporter;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\User;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $this->admin = User::factory()->create();
    $this->admin->assignRole($role);
    $this->actingAs($this->admin);

    $this->import = new Import;
    $this->import->user_id = $this->admin->id;
    $this->import->file_name = 'test.csv';
    $this->import->file_path = 'imports/test.csv';
    $this->import->importer = SupplierProductImporter::class;
    $this->import->total_rows = 0;
    $this->import->save();

    $this->columnMap = [
        'name' => 'name',
        'supplier' => 'supplier',
        'category' => 'category',
        'description' => 'description',
        'purchase_price' => 'purchase_price',
        'recommended_retail_price' => 'recommended_retail_price',
        'moq' => 'moq',
        'materials' => 'materials',
        'colors' => 'colors',
        'sizes' => 'sizes',
        'notes' => 'notes',
    ];

    $this->importer = $this->import->getImporter($this->columnMap, []);
});

it('importiert eine Lieferanten-Produkt-Zeile vollständig', function () {
    ($this->importer)([
        'name' => 'Pro Jersey Roh',
        'supplier' => 'Textiles Pro Portugal',
        'category' => 'Cycling Jerseys',
        'description' => 'Roher Stoff-Zuschnitt',
        'purchase_price' => '12.50',
        'recommended_retail_price' => '49.99',
        'moq' => '100',
        'materials' => 'Polyester,Elasthan',
        'colors' => 'Schwarz',
        'sizes' => 'S,M,L',
        'notes' => 'Eilauftrag möglich',
    ]);

    $product = SupplierProduct::where('name', 'Pro Jersey Roh')->first();

    expect($product)->not->toBeNull();
    expect($product->materials)->toBe(['Polyester', 'Elasthan']);
    expect($product->moq)->toBe(100);
    expect((float) $product->purchase_price)->toBe(12.50);
});

it('legt einen neuen Lieferanten an, wenn er noch nicht existiert', function () {
    expect(Supplier::where('name', 'Neuer Lieferant Y')->exists())->toBeFalse();

    ($this->importer)([
        'name' => 'Produkt mit neuem Lieferant',
        'supplier' => 'Neuer Lieferant Y',
        'category' => 'Test',
    ]);

    expect(Supplier::where('name', 'Neuer Lieferant Y')->exists())->toBeTrue();
});

it('verwendet vorhandene Lieferanten wieder', function () {
    $vorhanden = Supplier::factory()->create(['name' => 'Sofia Garments']);

    ($this->importer)([
        'name' => 'Produkt 1',
        'supplier' => 'Sofia Garments',
        'category' => 'Cat',
    ]);
    ($this->importer)([
        'name' => 'Produkt 2',
        'supplier' => 'Sofia Garments',
        'category' => 'Cat',
    ]);

    expect(Supplier::where('name', 'Sofia Garments')->count())->toBe(1);
    expect(SupplierProduct::where('supplier_id', $vorhanden->id)->count())->toBe(2);
});

it('verwirft Zeilen mit MOQ < 1 als ValidationException', function () {
    expect(fn () => ($this->importer)([
        'name' => 'Foo',
        'supplier' => 'Bar',
        'category' => 'Cat',
        'moq' => '0',
    ]))->toThrow(ValidationException::class);
});

it('legt eine neue Kategorie per Slug an', function () {
    ($this->importer)([
        'name' => 'Foo',
        'supplier' => 'Bar',
        'category' => 'Bib Shorts',
    ]);

    expect(Category::where('slug', 'bib-shorts')->exists())->toBeTrue();
});
