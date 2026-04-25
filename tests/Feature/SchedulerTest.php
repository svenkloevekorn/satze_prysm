<?php

use Illuminate\Console\Scheduling\Schedule;

it('hat staeze:fetch-channel-metrics als geplanten Task', function () {
    $schedule = app(Schedule::class);

    $events = collect($schedule->events());

    $matching = $events->filter(
        fn ($event) => str_contains($event->command ?? '', 'staeze:fetch-channel-metrics')
    );

    expect($matching)->not->toBeEmpty();
});

it('plant fetch-channel-metrics täglich um 03:00', function () {
    $schedule = app(Schedule::class);
    $events = collect($schedule->events());

    $event = $events->firstWhere(
        fn ($event) => str_contains($event->command ?? '', 'staeze:fetch-channel-metrics')
    );

    expect($event)->not->toBeNull();
    expect($event->expression)->toBe('0 3 * * *');
});
