<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

it('rendert die Login-Seite für anonyme Besucher', function () {
    $this->get('/admin/login')->assertSuccessful();
});

it('leitet anonyme Besucher von /admin auf /admin/login um', function () {
    $this->get('/admin')->assertRedirect();
});

it('lässt aktive super_admins ins Admin-Panel', function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole($role);

    $this->actingAs($user)
        ->get('/admin')
        ->assertSuccessful();
});

it('blockt deaktivierte Benutzer vom Admin-Panel', function () {
    $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create(['is_active' => false]);
    $user->assignRole($role);

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});
