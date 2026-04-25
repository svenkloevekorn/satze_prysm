<?php

use App\Settings\DefaultsSettings;
use App\Settings\FeatureSettings;
use App\Settings\IntegrationsSettings;
use Illuminate\Support\Facades\DB;

it('definiert genau die Liste der zu verschlüsselnden Felder', function () {
    expect(IntegrationsSettings::encrypted())->toBe([
        'shopify_admin_api_token',
        'shopware_client_secret',
        'meta_graph_token',
        'tiktok_business_access_token',
        'youtube_api_key',
        'dhl_api_key',
        'klaviyo_api_key',
        'postmark_server_token',
    ]);
});

it('verschlüsselt das Klaviyo-Token, sodass das Klartext nicht in der DB steht', function () {
    $settings = app(IntegrationsSettings::class);
    $settings->klaviyo_api_key = 'pk_klar_text_xyz';
    $settings->save();

    $row = DB::table('settings')
        ->where('group', 'integrations')
        ->where('name', 'klaviyo_api_key')
        ->value('payload');

    expect($row)->not->toContain('pk_klar_text_xyz');

    $reloaded = app(IntegrationsSettings::class);
    expect($reloaded->klaviyo_api_key)->toBe('pk_klar_text_xyz');
});

it('speichert nicht-verschlüsselte Felder im Klartext', function () {
    $settings = app(IntegrationsSettings::class);
    $settings->shopify_store_domain = 'mein-shop.myshopify.com';
    $settings->save();

    $row = DB::table('settings')
        ->where('group', 'integrations')
        ->where('name', 'shopify_store_domain')
        ->value('payload');

    expect($row)->toContain('mein-shop.myshopify.com');
});

it('speichert null-Werte korrekt für optionale Felder', function () {
    $settings = app(IntegrationsSettings::class);
    $settings->meta_graph_token = null;
    $settings->save();

    expect(app(IntegrationsSettings::class)->meta_graph_token)->toBeNull();
});

it('speichert Boolean-Settings korrekt', function () {
    $settings = app(FeatureSettings::class);
    $settings->csv_import_enabled = false;
    $settings->save();

    expect(app(FeatureSettings::class)->csv_import_enabled)->toBeFalse();

    $settings->csv_import_enabled = true;
    $settings->save();

    expect(app(FeatureSettings::class)->csv_import_enabled)->toBeTrue();
});

it('speichert Integer-Settings (csv_max_rows)', function () {
    $settings = app(FeatureSettings::class);
    $settings->csv_max_rows = 5000;
    $settings->save();

    expect(app(FeatureSettings::class)->csv_max_rows)->toBe(5000);
});

it('speichert Float-Settings (vat_default)', function () {
    $settings = app(DefaultsSettings::class);
    $settings->vat_default = 7.0;
    $settings->save();

    expect(app(DefaultsSettings::class)->vat_default)->toBe(7.0);
});

it('liefert die korrekte Group für jede Settings-Klasse', function () {
    expect(IntegrationsSettings::group())->toBe('integrations');
    expect(FeatureSettings::group())->toBe('features');
    expect(DefaultsSettings::group())->toBe('defaults');
});
