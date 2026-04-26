<?php

use App\Models\User;
use App\Policies\UserPolicy;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
});

it('blockt Editor vom Anlegen eines Users', function () {
    $editor = User::factory()->create();
    $editor->assignRole('editor');

    expect($editor->can('create', User::class))->toBeFalse();
});

it('blockt Editor vom Sehen der User-Liste', function () {
    $editor = User::factory()->create();
    $editor->assignRole('editor');

    expect($editor->can('viewAny', User::class))->toBeFalse();
});

it('blockt Editor vom Bearbeiten eines anderen Users', function () {
    $editor = User::factory()->create();
    $editor->assignRole('editor');
    $other = User::factory()->create();

    expect($editor->can('update', $other))->toBeFalse();
});

it('blockt Editor vom Loeschen eines anderen Users', function () {
    $editor = User::factory()->create();
    $editor->assignRole('editor');
    $other = User::factory()->create();

    expect($editor->can('delete', $other))->toBeFalse();
});

it('erlaubt super_admin alle User-Operationen via Gate::before', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');
    $other = User::factory()->create();

    expect($admin->can('viewAny', User::class))->toBeTrue();
    expect($admin->can('create', User::class))->toBeTrue();
    expect($admin->can('update', $other))->toBeTrue();
    expect($admin->can('delete', $other))->toBeTrue();
});

it('UserPolicy::delete returnt grundsätzlich false (nur Gate::before laesst super_admin durch)', function () {
    $admin = User::factory()->create();
    $other = User::factory()->create();

    $policy = new UserPolicy;
    expect($policy->delete($admin, $other))->toBeFalse();
});
