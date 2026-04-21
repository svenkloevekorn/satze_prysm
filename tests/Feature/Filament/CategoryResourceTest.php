<?php

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Models\Category;
use App\Models\User;
use Illuminate\Validation\ValidationException;

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

it('erlaubt eine Unterkategorie unter einer Oberkategorie', function () {
    $parent = Category::factory()->create(['name' => 'Shirts']);
    $child = Category::factory()->create([
        'name' => 'Langarm-Shirts',
        'parent_id' => $parent->id,
    ]);

    expect($child->parent->id)->toBe($parent->id);
    expect($parent->children)->toHaveCount(1);
    expect($child->fullName())->toBe('Shirts › Langarm-Shirts');
});

it('blockiert 3. Ebene (Sub-Sub-Kategorie)', function () {
    $parent = Category::factory()->create(['name' => 'Shirts']);
    $child = Category::factory()->create([
        'name' => 'Langarm',
        'parent_id' => $parent->id,
    ]);

    // Versuch: eine Kategorie unter die bereits verschachtelte Kategorie zu haengen
    expect(fn () => Category::factory()->create([
        'name' => 'Merino',
        'parent_id' => $child->id,
    ]))->toThrow(ValidationException::class);
});

it('blockiert das Selbst-Referenzieren', function () {
    $category = Category::factory()->create();

    expect(fn () => $category->update(['parent_id' => $category->id]))
        ->toThrow(ValidationException::class);
});

it('erkennt Top-Level-Kategorie', function () {
    $parent = Category::factory()->create();
    $child = Category::factory()->create(['parent_id' => $parent->id]);

    expect($parent->isTopLevel())->toBeTrue();
    expect($child->isTopLevel())->toBeFalse();
});
