<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class IntegrationsSettings extends Settings
{
    // Shop-Integrationen
    public ?string $shopify_store_domain = null;

    public ?string $shopify_admin_api_token = null;

    public ?string $shopware_base_url = null;

    public ?string $shopware_client_id = null;

    public ?string $shopware_client_secret = null;

    // Social Media
    public ?string $meta_graph_token = null;

    public ?string $tiktok_business_access_token = null;

    public ?string $youtube_api_key = null;

    // Versand / Logistik
    public ?string $dhl_account_number = null;

    public ?string $dhl_api_key = null;

    // Marketing
    public ?string $klaviyo_api_key = null;

    public ?string $postmark_server_token = null;

    // Monitoring
    public ?string $sentry_dsn = null;

    public static function group(): string
    {
        return 'integrations';
    }

    public static function encrypted(): array
    {
        return [
            'shopify_admin_api_token',
            'shopware_client_secret',
            'meta_graph_token',
            'tiktok_business_access_token',
            'youtube_api_key',
            'dhl_api_key',
            'klaviyo_api_key',
            'postmark_server_token',
        ];
    }
}
