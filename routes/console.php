<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Taeglich 03:00 Uhr: neue Metrics-Snapshots fuer aktive Social-Kanaele
// Aktiv, sobald Laravel-Scheduler lauft (cron: * * * * * cd /path && php artisan schedule:run)
// Ersetze SimulatedFetcher mit echten Platform-APIs wenn verfuegbar.
Schedule::command('staeze:fetch-channel-metrics')
    ->dailyAt('03:00')
    ->withoutOverlapping()
    ->onOneServer();
