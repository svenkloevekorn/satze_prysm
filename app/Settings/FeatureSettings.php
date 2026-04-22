<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FeatureSettings extends Settings
{
    public bool $influencer_monitoring_enabled = true;

    public bool $auto_fetch_metrics_enabled = false;

    public bool $csv_import_enabled = true;

    public int $csv_max_rows = 2000;

    public bool $media_gallery_enabled = true;

    public bool $quality_checklist_enabled = true;

    public static function group(): string
    {
        return 'features';
    }
}
