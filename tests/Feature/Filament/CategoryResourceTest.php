<?php

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
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

it('zeigt die Kategorien-Liste an', function () {
    Category::factory()->count(3)->create();

    livewire(ListCategories::class)
        ->assertSuccessful();
});

it('legt eine neue Kategorie an', function () {
    livewire(CreateCategory::class)
        ->fillForm([
            'name' => 'Test Kategorie',
            'slug' => 'test-kategorie',
            'sort_order' => 1,
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Category::where('slug', 'test-kategorie')->exists())->toBeTrue();
});

it('verlangt einen Namen', function () {
    livewire(CreateCategory::class)
        ->fillForm([
            'name' => '',
            'slug' => 'foo',
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

it('bearbeitet eine Kategorie', function () {
    $category = Category::factory()->create(['name' => 'Alt']);

    livewire(EditCategory::class, ['record' => $category->getRouteKey()])
        ->fillForm(['name' => 'Neu'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($category->fresh()->name)->toBe('Neu');
});
