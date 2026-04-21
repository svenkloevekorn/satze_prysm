<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

it('setzt Passwort zurueck und weist super_admin-Rolle zu', function () {
    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create(['email' => 'test-admin@example.com']);

    $this->artisan('staeze:reset-admin', [
        '--email' => 'test-admin@example.com',
        '--password' => 'neuespasswort',
    ])->assertSuccessful();

    $user->refresh();
    expect($user->hasRole('super_admin'))->toBeTrue();
    expect(Hash::check('neuespasswort', $user->password))->toBeTrue();
});

it('legt den Admin-User an wenn er nicht existiert', function () {
    Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

    $this->artisan('staeze:reset-admin', [
        '--email' => 'neu@example.com',
    ])->assertSuccessful();

    $user = User::where('email', 'neu@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->hasRole('super_admin'))->toBeTrue();
});
