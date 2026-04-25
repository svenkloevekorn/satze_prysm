<?php

use App\Filament\Pages\MedienGalerie;
use App\Models\CompetitorProduct;
use App\Models\User;

use function Pest\Livewire\livewire;

use Spatie\Permission\Models\Role;

beforeEach(function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    $this->actingAs($user);
});

it('rendert die Medien-Galerie-Page ohne Bilder', function () {
    livewire(MedienGalerie::class)->assertSuccessful();
});

it('rendert die Medien-Galerie-Page auch mit vorhandenen Produkten', function () {
    CompetitorProduct::factory()->count(3)->create();

    livewire(MedienGalerie::class)->assertSuccessful();
});
