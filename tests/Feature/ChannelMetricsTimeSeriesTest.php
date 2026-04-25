<?php

use App\Models\ChannelMetric;
use App\Models\SocialChannel;

it('liefert latestMetric=null wenn noch keine Snapshots existieren', function () {
    $channel = SocialChannel::factory()->create();

    expect($channel->latestMetric())->toBeNull();
});

it('sortiert Metrics nach captured_at und liefert den jüngsten als latestMetric', function () {
    $channel = SocialChannel::factory()->create();

    $alt = ChannelMetric::factory()->create([
        'social_channel_id' => $channel->id,
        'captured_at' => now()->subDays(10),
        'followers' => 1000,
    ]);
    $mittel = ChannelMetric::factory()->create([
        'social_channel_id' => $channel->id,
        'captured_at' => now()->subDays(5),
        'followers' => 1500,
    ]);
    $neu = ChannelMetric::factory()->create([
        'social_channel_id' => $channel->id,
        'captured_at' => now()->subDay(),
        'followers' => 2000,
    ]);

    expect($channel->latestMetric()->id)->toBe($neu->id);
    expect($channel->metrics)->toHaveCount(3);
});

it('hat eine HasMany-Relation zu ChannelMetric', function () {
    $channel = SocialChannel::factory()->create();
    ChannelMetric::factory()->count(5)->create(['social_channel_id' => $channel->id]);

    expect($channel->metrics()->count())->toBe(5);
});

it('verschiedene Channels haben getrennte Metrics', function () {
    $a = SocialChannel::factory()->create();
    $b = SocialChannel::factory()->create();

    ChannelMetric::factory()->count(3)->create(['social_channel_id' => $a->id]);
    ChannelMetric::factory()->count(2)->create(['social_channel_id' => $b->id]);

    expect($a->metrics()->count())->toBe(3);
    expect($b->metrics()->count())->toBe(2);
});
