<?php

/*
 * Nach dem Speichern eines Create- oder Edit-Formulars soll zur Listenansicht
 * redirected werden (via Trait App\Filament\Concerns\RedirectsToIndex).
 */

use App\Filament\Resources\Brands\BrandResource;
use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\User;

use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Role;

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    $this->actingAs($user);
});

it('redirectet nach Create zu Index (Category)', function () {
    livewire(CreateCategory::class)
        ->fillForm([
            'name' => 'Test',
            'slug' => 'test-redirect',
            'sort_order' => 1,
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertRedirect(CategoryResource::getUrl('index'));
});

it('redirectet nach Edit zu Index (Category)', function () {
    $cat = Category::factory()->create();

    livewire(EditCategory::class, ['record' => $cat->getRouteKey()])
        ->fillForm(['name' => 'Neuer Name'])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertRedirect(CategoryResource::getUrl('index'));
});

it('redirectet nach Create zu Index (Brand)', function () {
    livewire(CreateBrand::class)
        ->fillForm(['name' => 'Test-Brand', 'is_active' => true])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertRedirect(BrandResource::getUrl('index'));
});

it('redirectet nach Edit zu Index (Brand)', function () {
    $brand = Brand::factory()->create();

    livewire(EditBrand::class, ['record' => $brand->getRouteKey()])
        ->fillForm(['name' => 'Neuer Markenname'])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertRedirect(BrandResource::getUrl('index'));
});
