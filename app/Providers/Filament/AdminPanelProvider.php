<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\LetzteAenderungenWidget;
use App\Filament\Widgets\LetzteBewertungenWidget;
use App\Filament\Widgets\OffeneEntwicklungenWidget;
use App\Filament\Widgets\SocialStatsWidget;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TopChannelsWidget;
use App\Filament\Widgets\UnbewerteteProdukteWidget;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Prysm')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                StatsOverview::class,
                OffeneEntwicklungenWidget::class,
                UnbewerteteProdukteWidget::class,
                LetzteBewertungenWidget::class,
                LetzteAenderungenWidget::class,
                SocialStatsWidget::class,
                TopChannelsWidget::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('Marktanalyse')->collapsed(),
                NavigationGroup::make('Lieferanten')->collapsed(),
                NavigationGroup::make('Produkt-Entwicklung')->collapsed(),
                NavigationGroup::make('Bewertungen')->collapsed(),
                NavigationGroup::make('Medien')->collapsed(),
                NavigationGroup::make('Social Media')->collapsed(),
                NavigationGroup::make('System')->collapsed(),
            ])
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('System'),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
