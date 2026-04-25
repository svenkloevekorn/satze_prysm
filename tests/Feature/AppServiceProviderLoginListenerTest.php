<?php

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;

it('aktualisiert last_login_at wenn ein User sich einloggt', function () {
    $user = User::factory()->create(['last_login_at' => null]);

    expect($user->last_login_at)->toBeNull();

    Event::dispatch(new Login('web', $user, false));

    expect($user->fresh()->last_login_at)->not->toBeNull();
});

it('feuert KEINEN saved-Event (saveQuietly verhindert Listener-Loop)', function () {
    $user = User::factory()->create(['last_login_at' => null]);

    $savedFired = false;
    User::saved(function () use (&$savedFired) {
        $savedFired = true;
    });

    Event::dispatch(new Login('web', $user, false));

    expect($savedFired)->toBeFalse();
});
