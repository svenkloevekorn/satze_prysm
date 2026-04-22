<?php

namespace App\Filament\Pages;

use App\Settings\DefaultsSettings;
use App\Settings\FeatureSettings;
use App\Settings\IntegrationsSettings;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * @property Schema $form
 */
class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.manage-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $title = 'Einstellungen';

    protected static ?string $navigationLabel = 'Einstellungen';

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public function mount(): void
    {
        $integrations = app(IntegrationsSettings::class);
        $features = app(FeatureSettings::class);
        $defaults = app(DefaultsSettings::class);

        $this->form->fill([
            'shopify_store_domain' => $integrations->shopify_store_domain,
            'shopify_admin_api_token' => $integrations->shopify_admin_api_token,
            'shopware_base_url' => $integrations->shopware_base_url,
            'shopware_client_id' => $integrations->shopware_client_id,
            'shopware_client_secret' => $integrations->shopware_client_secret,
            'meta_graph_token' => $integrations->meta_graph_token,
            'tiktok_business_access_token' => $integrations->tiktok_business_access_token,
            'youtube_api_key' => $integrations->youtube_api_key,
            'dhl_account_number' => $integrations->dhl_account_number,
            'dhl_api_key' => $integrations->dhl_api_key,
            'klaviyo_api_key' => $integrations->klaviyo_api_key,
            'postmark_server_token' => $integrations->postmark_server_token,
            'sentry_dsn' => $integrations->sentry_dsn,
            'influencer_monitoring_enabled' => $features->influencer_monitoring_enabled,
            'auto_fetch_metrics_enabled' => $features->auto_fetch_metrics_enabled,
            'csv_import_enabled' => $features->csv_import_enabled,
            'csv_max_rows' => $features->csv_max_rows,
            'media_gallery_enabled' => $features->media_gallery_enabled,
            'quality_checklist_enabled' => $features->quality_checklist_enabled,
            'currency' => $defaults->currency,
            'vat_default' => $defaults->vat_default,
            'company_name' => $defaults->company_name,
            'company_email' => $defaults->company_email,
            'country' => $defaults->country,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('settings')
                ->tabs([
                    Tab::make('Integrationen')
                        ->icon('heroicon-o-globe-alt')
                        ->schema([
                            Section::make('Shop')
                                ->description('Shop-Integration (später). Tokens werden verschlüsselt gespeichert.')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('shopify_store_domain')
                                        ->label('Shopify Store Domain')
                                        ->placeholder('mystore.myshopify.com'),
                                    TextInput::make('shopify_admin_api_token')
                                        ->label('Shopify Admin API Token')
                                        ->password()
                                        ->revealable(),
                                    TextInput::make('shopware_base_url')
                                        ->label('Shopware Base URL')
                                        ->url(),
                                    TextInput::make('shopware_client_id')
                                        ->label('Shopware Client ID'),
                                    TextInput::make('shopware_client_secret')
                                        ->label('Shopware Client Secret')
                                        ->password()
                                        ->revealable()
                                        ->columnSpanFull(),
                                ]),
                            Section::make('Social Media')
                                ->description('Für automatisches Metrics-Fetching (Instagram, TikTok, YouTube).')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('meta_graph_token')
                                        ->label('Meta Graph API Token')
                                        ->password()
                                        ->revealable(),
                                    TextInput::make('tiktok_business_access_token')
                                        ->label('TikTok Business Access Token')
                                        ->password()
                                        ->revealable(),
                                    TextInput::make('youtube_api_key')
                                        ->label('YouTube Data API Key')
                                        ->password()
                                        ->revealable()
                                        ->columnSpanFull(),
                                ]),
                            Section::make('Versand & Logistik')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('dhl_account_number')
                                        ->label('DHL Account Number'),
                                    TextInput::make('dhl_api_key')
                                        ->label('DHL API Key')
                                        ->password()
                                        ->revealable(),
                                ]),
                            Section::make('Marketing & Monitoring')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('klaviyo_api_key')
                                        ->label('Klaviyo API Key')
                                        ->password()
                                        ->revealable(),
                                    TextInput::make('postmark_server_token')
                                        ->label('Postmark Server Token')
                                        ->password()
                                        ->revealable(),
                                    TextInput::make('sentry_dsn')
                                        ->label('Sentry DSN')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Tab::make('Features')
                        ->icon('heroicon-o-sparkles')
                        ->schema([
                            Section::make('Module an-/abschalten')
                                ->schema([
                                    Toggle::make('influencer_monitoring_enabled')
                                        ->label('Influencer-Monitoring aktiv'),
                                    Toggle::make('auto_fetch_metrics_enabled')
                                        ->label('Auto-Fetch Social-Metrics (Cronjob)')
                                        ->helperText('Aktiv sobald Scheduler + echte APIs bereit sind.'),
                                    Toggle::make('csv_import_enabled')
                                        ->label('CSV-Import erlaubt'),
                                    TextInput::make('csv_max_rows')
                                        ->label('CSV max. Zeilen pro Import')
                                        ->numeric()
                                        ->minValue(100)
                                        ->maxValue(100000),
                                    Toggle::make('media_gallery_enabled')
                                        ->label('Medien-Galerie aktiv'),
                                    Toggle::make('quality_checklist_enabled')
                                        ->label('Qualitäts-Checkliste aktiv'),
                                ]),
                        ]),
                    Tab::make('Defaults')
                        ->icon('heroicon-o-adjustments-horizontal')
                        ->schema([
                            Section::make('Standard-Werte')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('currency')
                                        ->label('Währung')
                                        ->maxLength(3),
                                    TextInput::make('vat_default')
                                        ->label('Standard-MwSt (%)')
                                        ->numeric()
                                        ->step(0.1),
                                    TextInput::make('company_name')
                                        ->label('Firmenname'),
                                    TextInput::make('company_email')
                                        ->label('Firma E-Mail')
                                        ->email(),
                                    TextInput::make('country')
                                        ->label('Standard-Land (ISO-Code)')
                                        ->maxLength(2),
                                ]),
                        ]),
                ])
                ->persistTabInQueryString(),
        ])->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $integrations = app(IntegrationsSettings::class);
        foreach ([
            'shopify_store_domain', 'shopify_admin_api_token',
            'shopware_base_url', 'shopware_client_id', 'shopware_client_secret',
            'meta_graph_token', 'tiktok_business_access_token', 'youtube_api_key',
            'dhl_account_number', 'dhl_api_key',
            'klaviyo_api_key', 'postmark_server_token', 'sentry_dsn',
        ] as $key) {
            $integrations->$key = $data[$key] ?? null;
        }
        $integrations->save();

        $features = app(FeatureSettings::class);
        foreach ([
            'influencer_monitoring_enabled',
            'auto_fetch_metrics_enabled',
            'csv_import_enabled',
            'csv_max_rows',
            'media_gallery_enabled',
            'quality_checklist_enabled',
        ] as $key) {
            $features->$key = $data[$key] ?? $features->$key;
        }
        $features->save();

        $defaults = app(DefaultsSettings::class);
        foreach (['currency', 'vat_default', 'company_name', 'company_email', 'country'] as $key) {
            $defaults->$key = $data[$key] ?? $defaults->$key;
        }
        $defaults->save();

        Notification::make()->title('Einstellungen gespeichert')->success()->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')->label('Speichern')->submit('save'),
        ];
    }
}
