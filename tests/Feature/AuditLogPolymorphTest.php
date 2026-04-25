<?php

use App\Enums\DevelopmentStatus;
use App\Models\Brand;
use App\Models\DevelopmentItem;
use App\Models\Influencer;
use App\Models\Supplier;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('loggt das Anlegen eines Brands', function () {
    Brand::create(['name' => 'Test Brand', 'is_active' => true]);

    $activity = Activity::query()->where('subject_type', 'brand')->latest()->first();
    expect($activity)->not->toBeNull();
    expect($activity->event)->toBe('created');
});

it('loggt das Anlegen eines Suppliers (voller Klassenname als Subject-Type)', function () {
    Supplier::create(['name' => 'Test Supplier', 'is_active' => true]);

    // Supplier ist NICHT in der Morph-Map -> voller Klassenname
    $activity = Activity::query()->where('subject_type', Supplier::class)->latest()->first();
    expect($activity)->not->toBeNull();
});

it('loggt das Anlegen eines Influencers', function () {
    Influencer::create(['name' => 'Marie', 'is_active' => true]);

    $activity = Activity::query()->where('subject_type', 'influencer')->latest()->first();
    expect($activity)->not->toBeNull();
});

it('loggt eine Statusänderung am DevelopmentItem als updated-Event', function () {
    $item = DevelopmentItem::factory()->create(['status' => DevelopmentStatus::Idea]);
    $item->activitiesAsSubject()->delete();

    $item->update(['status' => DevelopmentStatus::InProgress]);

    $activity = $item->activitiesAsSubject()->where('event', 'updated')->latest()->first();
    expect($activity)->not->toBeNull();
});

it('loggt den verantwortlichen User per causer-Relation', function () {
    Brand::create(['name' => 'Causer Test', 'is_active' => true]);

    $activity = Activity::query()->where('subject_type', 'brand')->latest()->first();
    expect($activity->causer_id)->toBe($this->user->id);
    expect($activity->causer_type)->toBe(User::class);
});
