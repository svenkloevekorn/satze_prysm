<?php

/*
 * Lädt alle Edit-Seiten mit realistischen DB-Daten (JSON-Spalten befüllt).
 * Fängt Regressionen ab, bei denen eine Filament-Query Postgres-Fehler wirft
 * (z.B. "could not identify equality operator for type json").
 */

use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\CompetitorProducts\Pages\EditCompetitorProduct;
use App\Filament\Resources\DevelopmentItems\Pages\EditDevelopmentItem;
use App\Filament\Resources\FinalProducts\Pages\EditFinalProduct;
use App\Filament\Resources\QualityCriteria\Pages\EditQualityCriterion;
use App\Filament\Resources\RatingDimensions\Pages\EditRatingDimension;
use App\Filament\Resources\Ratings\Pages\EditRating;
use App\Filament\Resources\Shops\Pages\EditShop;
use App\Filament\Resources\SupplierProducts\Pages\EditSupplierProduct;
use App\Filament\Resources\Suppliers\Pages\EditSupplier;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CompetitorProduct;
use App\Models\DevelopmentItem;
use App\Models\FinalProduct;
use App\Models\QualityCriterion;
use App\Models\Rating;
use App\Models\RatingDimension;
use App\Models\Shop;
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

    // Realistisches DB-Szenario: JSON-Spalten befüllt, Relationen angelegt
    CompetitorProduct::factory()->count(2)->create();
    SupplierProduct::factory()->count(2)->create();
    SupplierContact::factory()->create();
    DevelopmentItem::factory()->create();
    FinalProduct::factory()->create();
});

$resources = [
    ['model' => Category::class, 'edit' => EditCategory::class],
    ['model' => Brand::class, 'edit' => EditBrand::class],
    ['model' => Shop::class, 'edit' => EditShop::class],
    ['model' => RatingDimension::class, 'edit' => EditRatingDimension::class],
    ['model' => QualityCriterion::class, 'edit' => EditQualityCriterion::class],
    ['model' => CompetitorProduct::class, 'edit' => EditCompetitorProduct::class],
    ['model' => Supplier::class, 'edit' => EditSupplier::class],
    ['model' => SupplierProduct::class, 'edit' => EditSupplierProduct::class],
    ['model' => DevelopmentItem::class, 'edit' => EditDevelopmentItem::class],
    ['model' => FinalProduct::class, 'edit' => EditFinalProduct::class],
    ['model' => Rating::class, 'edit' => EditRating::class],
];

foreach ($resources as $resource) {
    it("laedt die Edit-Seite von {$resource['model']} ohne Fehler", function () use ($resource) {
        $record = $resource['model']::factory()->create();

        livewire($resource['edit'], ['record' => $record->getRouteKey()])
            ->assertSuccessful();
    });
}
