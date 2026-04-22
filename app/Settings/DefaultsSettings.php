<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DefaultsSettings extends Settings
{
    public string $currency = 'EUR';

    public float $vat_default = 19.0;

    public string $company_name = 'Staeze';

    public string $company_email = 'kontakt@staeze.example';

    public ?int $default_supplier_id = null;

    public string $country = 'DE';

    public static function group(): string
    {
        return 'defaults';
    }
}
