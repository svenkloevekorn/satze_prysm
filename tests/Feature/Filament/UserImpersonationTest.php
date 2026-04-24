<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
});

it('erlaubt einem super_admin zu impersonieren', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');

    expect($admin->canImpersonate())->toBeTrue();
});

it('verbietet Nicht-super_admins zu impersonieren', function () {
    $editor = User::factory()->create();
    $editor->assignRole('editor');

    expect($editor->canImpersonate())->toBeFalse();
});

it('lässt aktive Nicht-super_admins impersoniert werden', function () {
    $target = User::factory()->create(['is_active' => true]);
    $target->assignRole('editor');

    expect($target->canBeImpersonated())->toBeTrue();
});

it('schützt super_admins vor Impersonation', function () {
    $other = User::factory()->create(['is_active' => true]);
    $other->assignRole('super_admin');

    expect($other->canBeImpersonated())->toBeFalse();
});

it('schützt deaktivierte Benutzer vor Impersonation', function () {
    $deactivated = User::factory()->create(['is_active' => false]);
    $deactivated->assignRole('editor');

    expect($deactivated->canBeImpersonated())->toBeFalse();
});
