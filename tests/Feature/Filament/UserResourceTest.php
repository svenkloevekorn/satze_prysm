<?php

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Panel;
use Illuminate\Support\Facades\Hash;

use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Role;

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $admin = User::factory()->create();
    $admin->assignRole($role);
    $this->actingAs($admin);
});

it('zeigt die Benutzer-Liste an', function () {
    User::factory()->count(3)->create();
    livewire(ListUsers::class)->assertSuccessful();
});

it('legt einen neuen Benutzer an', function () {
    livewire(CreateUser::class)
        ->fillForm([
            'name' => 'Neuer Mitarbeiter',
            'email' => 'neu@example.com',
            'password' => 'geheim1234',
            'password_confirmation' => 'geheim1234',
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::where('email', 'neu@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->is_active)->toBeTrue();
    expect($user->name)->toBe('Neuer Mitarbeiter');
    expect(Hash::check('geheim1234', $user->password))->toBeTrue();
});

it('verlangt Passwort-Bestaetigung beim Anlegen', function () {
    livewire(CreateUser::class)
        ->fillForm([
            'name' => 'X',
            'email' => 'x@example.com',
            'password' => 'geheim1234',
            'password_confirmation' => 'anders1234',
        ])
        ->call('create')
        ->assertHasFormErrors(['password_confirmation']);
});

it('validiert eindeutige E-Mail', function () {
    User::factory()->create(['email' => 'vorhanden@example.com']);

    livewire(CreateUser::class)
        ->fillForm([
            'name' => 'Duplikat',
            'email' => 'vorhanden@example.com',
            'password' => 'geheim1234',
            'password_confirmation' => 'geheim1234',
        ])
        ->call('create')
        ->assertHasFormErrors(['email']);
});

it('weist einem Benutzer Rollen zu', function () {
    $editorRole = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);

    livewire(CreateUser::class)
        ->fillForm([
            'name' => 'Mit Rolle',
            'email' => 'role@example.com',
            'password' => 'geheim1234',
            'password_confirmation' => 'geheim1234',
            'roles' => [$editorRole->id],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::where('email', 'role@example.com')->first();
    expect($user->hasRole('editor'))->toBeTrue();
});

it('aendert Passwort beim Bearbeiten nur wenn angegeben', function () {
    $user = User::factory()->create(['password' => bcrypt('original-pw')]);

    livewire(EditUser::class, ['record' => $user->getRouteKey()])
        ->fillForm(['name' => 'Neuer Name'])
        ->call('save')
        ->assertHasNoFormErrors();

    $user->refresh();
    expect($user->name)->toBe('Neuer Name');
    expect(Hash::check('original-pw', $user->password))->toBeTrue();
});

it('sperrt deaktivierte Benutzer vom Panel-Zugriff', function () {
    $user = User::factory()->create(['is_active' => false]);
    expect($user->canAccessPanel(app(Panel::class)))->toBeFalse();

    $active = User::factory()->create(['is_active' => true]);
    expect($active->canAccessPanel(app(Panel::class)))->toBeTrue();
});
