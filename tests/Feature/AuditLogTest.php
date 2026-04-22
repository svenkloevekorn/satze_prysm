<?php

use App\Models\CompetitorProduct;
use App\Models\User;

beforeEach(function () {
    $user = User::factory()->create();
    $this->actingAs($user);
});

it('loggt das Anlegen eines Wettbewerbsprodukts', function () {
    $product = CompetitorProduct::factory()->create(['name' => 'Log-Test']);

    expect($product->activitiesAsSubject()->count())->toBeGreaterThanOrEqual(1);
    expect($product->activitiesAsSubject()->where('event', 'created')->exists())->toBeTrue();
});

it('loggt Aenderungen am Feld co2_kg', function () {
    $product = CompetitorProduct::factory()->create(['co2_kg' => null]);
    $product->update(['co2_kg' => 12.50]);

    $updated = $product->activitiesAsSubject()->where('event', 'updated')->latest()->first();
    expect($updated)->not->toBeNull();

    $changes = $updated->attribute_changes;
    expect($changes)->toHaveKey('attributes');
    expect($changes['attributes'])->toHaveKey('co2_kg');
});

it('loggt keinen leeren Change', function () {
    $product = CompetitorProduct::factory()->create(['name' => 'Unchanged']);
    $countBefore = $product->activitiesAsSubject()->count();

    $product->update(['name' => 'Unchanged']);

    expect($product->activitiesAsSubject()->count())->toBe($countBefore);
});
