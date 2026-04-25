<?php

use App\Models\ChannelMetric;
use App\Models\SocialChannel;

it('legt Snapshots für alle aktiven Channels an', function () {
    SocialChannel::factory()->count(3)->create(['is_active' => true]);

    $exitCode = $this->artisan('staeze:fetch-channel-metrics')
        ->expectsOutputToContain('3 Snapshots erstellt')
        ->run();

    expect($exitCode)->toBe(0);
    expect(ChannelMetric::count())->toBe(3);
});

it('überspringt inaktive Channels', function () {
    SocialChannel::factory()->create(['is_active' => true]);
    SocialChannel::factory()->count(2)->create(['is_active' => false]);

    $this->artisan('staeze:fetch-channel-metrics')->run();

    expect(ChannelMetric::count())->toBe(1);
});

it('ist idempotent für denselben Tag', function () {
    SocialChannel::factory()->create(['is_active' => true]);

    $this->artisan('staeze:fetch-channel-metrics')->run();
    $this->artisan('staeze:fetch-channel-metrics')->run();

    expect(ChannelMetric::count())->toBe(1);
});

it('aktualisiert die followers- und engagement_rate-Stammwerte am Channel', function () {
    $channel = SocialChannel::factory()->create([
        'is_active' => true,
        'followers' => 100,
        'engagement_rate' => 0.5,
    ]);

    $this->artisan('staeze:fetch-channel-metrics')->run();

    $channel->refresh();
    expect($channel->followers)->toBeGreaterThan(0);
    expect((float) $channel->engagement_rate)->toBeGreaterThan(0);
});

it('verarbeitet nur einen einzelnen Channel mit --channel-Option', function () {
    $target = SocialChannel::factory()->create(['is_active' => true]);
    SocialChannel::factory()->count(2)->create(['is_active' => true]);

    $this->artisan('staeze:fetch-channel-metrics', ['--channel' => $target->id])->run();

    expect(ChannelMetric::count())->toBe(1);
    expect(ChannelMetric::first()->social_channel_id)->toBe($target->id);
});

it('warnt wenn keine aktiven Channels da sind', function () {
    $this->artisan('staeze:fetch-channel-metrics')
        ->expectsOutputToContain('Keine aktiven Kanäle')
        ->assertExitCode(0);

    expect(ChannelMetric::count())->toBe(0);
});
