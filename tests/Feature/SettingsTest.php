<?php

use App\Settings\DefaultsSettings;
use App\Settings\FeatureSettings;
use App\Settings\IntegrationsSettings;

it('liest und schreibt FeatureSettings', function () {
    $settings = app(FeatureSettings::class);
    $settings->csv_max_rows = 5000;
    $settings->save();

    $reload = app()->make(FeatureSettings::class);
    expect($reload->csv_max_rows)->toBe(5000);
});

it('liest und schreibt DefaultsSettings', function () {
    $settings = app(DefaultsSettings::class);
    $settings->currency = 'USD';
    $settings->vat_default = 7.0;
    $settings->save();

    $reload = app()->make(DefaultsSettings::class);
    expect($reload->currency)->toBe('USD');
    expect($reload->vat_default)->toBe(7.0);
});

it('verschluesselt API-Keys in IntegrationsSettings', function () {
    $settings = app(IntegrationsSettings::class);
    $settings->shop_api_key = 'super-secret-token-123';
    $settings->save();

    $raw = DB::table('settings')
        ->where('group', 'integrations')
        ->where('name', 'shop_api_key')
        ->value('payload');

    expect((string) $raw)->not->toContain('super-secret-token-123');

    $reload = app()->make(IntegrationsSettings::class);
    expect($reload->shop_api_key)->toBe('super-secret-token-123');
});
