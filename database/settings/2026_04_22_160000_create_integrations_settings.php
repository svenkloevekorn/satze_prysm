<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Integrations (alle nullable – werden erst gesetzt, wenn es soweit ist)
        $this->migrator->add('integrations.shopify_store_domain', null);
        $this->migrator->add('integrations.shopify_admin_api_token', null);
        $this->migrator->add('integrations.shopware_base_url', null);
        $this->migrator->add('integrations.shopware_client_id', null);
        $this->migrator->add('integrations.shopware_client_secret', null);
        $this->migrator->add('integrations.meta_graph_token', null);
        $this->migrator->add('integrations.tiktok_business_access_token', null);
        $this->migrator->add('integrations.youtube_api_key', null);
        $this->migrator->add('integrations.dhl_account_number', null);
        $this->migrator->add('integrations.dhl_api_key', null);
        $this->migrator->add('integrations.klaviyo_api_key', null);
        $this->migrator->add('integrations.postmark_server_token', null);
        $this->migrator->add('integrations.sentry_dsn', null);

        $this->migrator->encrypt('integrations.shopify_admin_api_token');
        $this->migrator->encrypt('integrations.shopware_client_secret');
        $this->migrator->encrypt('integrations.meta_graph_token');
        $this->migrator->encrypt('integrations.tiktok_business_access_token');
        $this->migrator->encrypt('integrations.youtube_api_key');
        $this->migrator->encrypt('integrations.dhl_api_key');
        $this->migrator->encrypt('integrations.klaviyo_api_key');
        $this->migrator->encrypt('integrations.postmark_server_token');

        // Features
        $this->migrator->add('features.influencer_monitoring_enabled', true);
        $this->migrator->add('features.auto_fetch_metrics_enabled', false);
        $this->migrator->add('features.csv_import_enabled', true);
        $this->migrator->add('features.csv_max_rows', 2000);
        $this->migrator->add('features.media_gallery_enabled', true);
        $this->migrator->add('features.quality_checklist_enabled', true);

        // Defaults
        $this->migrator->add('defaults.currency', 'EUR');
        $this->migrator->add('defaults.vat_default', 19.0);
        $this->migrator->add('defaults.company_name', 'Staeze');
        $this->migrator->add('defaults.company_email', 'kontakt@staeze.example');
        $this->migrator->add('defaults.default_supplier_id', null);
        $this->migrator->add('defaults.country', 'DE');
    }
};
