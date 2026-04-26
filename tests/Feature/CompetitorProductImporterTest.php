<?php

use App\Filament\Imports\CompetitorProductImporter;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CompetitorProduct;
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
    $this->import->importer = CompetitorProductImporter::class;
    $this->import->total_rows = 0;
    $this->import->save();

    $this->columnMap = [
        'name' => 'name',
        'brand' => 'brand',
        'category' => 'category',
        'description' => 'description',
        'materials' => 'materials',
        'colors' => 'colors',
        'sizes' => 'sizes',
        'price_min' => 'price_min',
        'price_max' => 'price_max',
    ];

    $this->importer = $this->import->getImporter($this->columnMap, []);
});

it('importiert eine vollständige CSV-Zeile als CompetitorProduct', function () {
    ($this->importer)([
        'name' => 'Castelli Climbers Jersey',
        'brand' => 'Castelli',
        'category' => 'Cycling Jerseys',
        'description' => 'Leichtes Klettertrikot',
        'materials' => 'Polyester,Elasthan',
        'colors' => 'Schwarz,Rot',
        'sizes' => 'S,M,L,XL',
        'price_min' => '99.99',
        'price_max' => '139.99',
    ]);

    $product = CompetitorProduct::where('name', 'Castelli Climbers Jersey')->first();

    expect($product)->not->toBeNull();
    expect($product->materials)->toBe(['Polyester', 'Elasthan']);
    expect($product->colors)->toBe(['Schwarz', 'Rot']);
    expect($product->sizes)->toBe(['S', 'M', 'L', 'XL']);
    expect((float) $product->price_min)->toBe(99.99);
    expect((float) $product->price_max)->toBe(139.99);
});

it('legt eine neue Marke an, wenn sie noch nicht existiert', function () {
    expect(Brand::where('name', 'Neue Marke X')->exists())->toBeFalse();

    ($this->importer)([
        'name' => 'Produkt mit neuer Marke',
        'brand' => 'Neue Marke X',
        'category' => 'Test-Kategorie',
    ]);

    expect(Brand::where('name', 'Neue Marke X')->exists())->toBeTrue();
});

it('verwendet eine vorhandene Marke wieder', function () {
    $vorhandene = Brand::factory()->create(['name' => 'Castelli']);

    ($this->importer)([
        'name' => 'Trikot 1',
        'brand' => 'Castelli',
        'category' => 'Jerseys',
    ]);
    ($this->importer)([
        'name' => 'Trikot 2',
        'brand' => 'Castelli',
        'category' => 'Jerseys',
    ]);

    expect(Brand::where('name', 'Castelli')->count())->toBe(1);
    expect(CompetitorProduct::where('brand_id', $vorhandene->id)->count())->toBe(2);
});

it('legt eine neue Kategorie per Slug an', function () {
    ($this->importer)([
        'name' => 'Foo',
        'brand' => 'Bar',
        'category' => 'Cycling Jerseys',
    ]);

    $cat = Category::where('slug', 'cycling-jerseys')->first();
    expect($cat)->not->toBeNull();
    expect($cat->name)->toBe('Cycling Jerseys');
});

it('wirft ValidationException bei fehlendem Namen', function () {
    expect(fn () => ($this->importer)([
        'name' => '',
        'brand' => 'Castelli',
        'category' => 'Jerseys',
    ]))->toThrow(ValidationException::class);

    expect(CompetitorProduct::where('brand', 'Castelli')->exists())->toBeFalse();
});
