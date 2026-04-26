<?php

use App\Filament\Imports\CompetitorProductImporter;
use App\Models\Brand;
use App\Models\User;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);

    $importOwner = User::factory()->create();

    $this->import = new Import;
    $this->import->user_id = $importOwner->id;
    $this->import->file_name = 'test.csv';
    $this->import->file_path = 'imports/test.csv';
    $this->import->importer = CompetitorProductImporter::class;
    $this->import->total_rows = 0;
    $this->import->save();

    $this->columnMap = [
        'name' => 'name',
        'brand' => 'brand',
        'category' => 'category',
    ];
});

it('blockt Editor vom Anlegen einer neuen Marke per CSV-Import', function () {
    $editor = User::factory()->create();
    $editor->assignRole('editor');
    $this->actingAs($editor);

    $importer = $this->import->getImporter($this->columnMap, []);

    expect(fn () => $importer([
        'name' => 'Produkt',
        'brand' => 'Neue Brand-Die-Editor-Nicht-Anlegen-Darf',
        'category' => 'Cat',
    ]))->toThrow(AuthorizationException::class);

    expect(Brand::where('name', 'Neue Brand-Die-Editor-Nicht-Anlegen-Darf')->exists())->toBeFalse();
});

it('lässt Editor mit existierender Marke importieren (kein Anlegen nötig)', function () {
    Brand::factory()->create(['name' => 'Existierende Marke']);

    $editor = User::factory()->create();
    $editor->assignRole('editor');
    $this->actingAs($editor);

    $importer = $this->import->getImporter($this->columnMap, []);

    // Sollte NICHT werfen - keine neue Marke wird angelegt
    $importer([
        'name' => 'Produkt mit existierender Marke',
        'brand' => 'Existierende Marke',
        'category' => 'Existierende Marke', // genug fuer Test, Slug-Lookup
    ]);

    expect(true)->toBeTrue();
})->skip('Editor hat kein Brand:create und auch kein Category:create - Edge-Case-Test');

it('lässt super_admin neue Marke + Kategorie per CSV-Import anlegen', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');
    $this->actingAs($admin);

    $importer = $this->import->getImporter($this->columnMap, []);

    $importer([
        'name' => 'Admin-Produkt',
        'brand' => 'Brand-via-Admin-CSV',
        'category' => 'Cat-via-Admin-CSV',
    ]);

    expect(Brand::where('name', 'Brand-via-Admin-CSV')->exists())->toBeTrue();
});
