<?php

namespace App\Filament\Widgets;

use App\Enums\DevelopmentStatus;
use App\Models\CompetitorProduct;
use App\Models\DevelopmentItem;
use App\Models\FinalProduct;
use App\Models\Supplier;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        return [
            Stat::make('Wettbewerbsprodukte', CompetitorProduct::count())
                ->description('gesamt im Markt-Radar')
                ->descriptionIcon('heroicon-o-magnifying-glass')
                ->color('primary'),

            Stat::make('Offene Entwicklungen', DevelopmentItem::whereNot('status', DevelopmentStatus::Final)->count())
                ->description('nicht-finale Items in der Pipeline')
                ->descriptionIcon('heroicon-o-light-bulb')
                ->color('info'),

            Stat::make('Finale Produkte', FinalProduct::count())
                ->description('bereits im Sortiment')
                ->descriptionIcon('heroicon-o-trophy')
                ->color('success'),

            Stat::make('Lieferanten', Supplier::where('is_active', true)->count())
                ->description('aktive Partner')
                ->descriptionIcon('heroicon-o-building-office')
                ->color('warning'),
        ];
    }
}
