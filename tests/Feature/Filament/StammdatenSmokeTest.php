<?php

use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\ListBrands;
use App\Filament\Resources\QualityCriteria\Pages\CreateQualityCriterion;
use App\Filament\Resources\QualityCriteria\Pages\ListQualityCriteria;
use App\Filament\Resources\RatingDimensions\Pages\CreateRatingDimension;
use App\Filament\Resources\RatingDimensions\Pages\ListRatingDimensions;
use App\Filament\Resources\Shops\Pages\CreateShop;
use App\Filament\Resources\Shops\Pages\ListShops;
use App\Models\Brand;
use App\Models\QualityCriterion;
use App\Models\RatingDimension;
use App\Models\Shop;
use App\Models\User;

use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Role;

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    $this->actingAs($user);
});

it('zeigt die Marken-Liste an', function () {
    Brand::factory()->count(3)->create();
    livewire(ListBrands::class)->assertSuccessful();
});

it('legt eine neue Marke an', function () {
    livewire(CreateBrand::class)
        ->fillForm(['name' => 'Castelli', 'is_active' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Brand::where('name', 'Castelli')->exists())->toBeTrue();
});

it('speichert Marken-Website und Social-Media-Kanäle', function () {
    livewire(CreateBrand::class)
        ->fillForm([
            'name' => 'Rapha',
            'country' => 'GB',
            'website' => 'https://www.rapha.cc',
            'instagram' => 'rapha',
            'facebook' => 'https://www.facebook.com/rapha',
            'linkedin' => 'https://www.linkedin.com/company/rapha',
            'tiktok' => 'rapha.cc',
            'youtube' => 'https://www.youtube.com/@rapha',
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $brand = Brand::where('name', 'Rapha')->first();
    expect($brand->country)->toBe('GB');
    expect($brand->website)->toBe('https://www.rapha.cc');
    expect($brand->instagram)->toBe('rapha');
    expect($brand->facebook)->toBe('https://www.facebook.com/rapha');
    expect($brand->tiktok)->toBe('rapha.cc');
});

it('validiert Website-URL bei Marken', function () {
    livewire(CreateBrand::class)
        ->fillForm([
            'name' => 'Test',
            'website' => 'kein-link',
        ])
        ->call('create')
        ->assertHasFormErrors(['website']);
});

it('zeigt die Shop-Liste an', function () {
    Shop::factory()->count(3)->create();
    livewire(ListShops::class)->assertSuccessful();
});

it('legt einen neuen Shop an', function () {
    livewire(CreateShop::class)
        ->fillForm([
            'name' => 'Test Shop',
            'url' => 'https://example.com',
            'country' => 'DE',
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Shop::where('name', 'Test Shop')->exists())->toBeTrue();
});

it('zeigt die Bewertungs-Dimensionen an', function () {
    RatingDimension::factory()->count(3)->create();
    livewire(ListRatingDimensions::class)->assertSuccessful();
});

it('legt eine neue Bewertungs-Dimension an', function () {
    livewire(CreateRatingDimension::class)
        ->fillForm([
            'name' => 'Komfort',
            'sort_order' => 1,
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(RatingDimension::where('name', 'Komfort')->exists())->toBeTrue();
});

it('zeigt die Qualitätskriterien an', function () {
    QualityCriterion::factory()->count(3)->create();
    livewire(ListQualityCriteria::class)->assertSuccessful();
});

it('legt ein neues Qualitätskriterium an', function () {
    livewire(CreateQualityCriterion::class)
        ->fillForm([
            'name' => 'UV-Schutz',
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(QualityCriterion::where('name', 'UV-Schutz')->exists())->toBeTrue();
});
