<?php

namespace App\Filament\Widgets;

use App\Models\ChannelMetric;
use App\Models\Influencer;
use App\Models\SocialChannel;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SocialStatsWidget extends StatsOverviewWidget
{
    protected ?string $pollingInterval = null;

    protected ?string $heading = 'Social Media Kennzahlen';

    protected static ?int $sort = 6;

    protected function getStats(): array
    {
        $totalReach = SocialChannel::where('is_active', true)->sum('followers');
        $avgEngagement = (float) SocialChannel::where('is_active', true)
            ->whereNotNull('engagement_rate')
            ->avg('engagement_rate');

        $growth30d = $this->followerGrowthLast30Days();

        return [
            Stat::make('Influencer aktiv', Influencer::where('is_active', true)->count())
                ->description('im System gepflegt')
                ->descriptionIcon('heroicon-o-user-circle')
                ->color('primary'),

            Stat::make('Aktive Kanäle', SocialChannel::where('is_active', true)->count())
                ->description('über alle Plattformen')
                ->descriptionIcon('heroicon-o-chat-bubble-left-right')
                ->color('info'),

            Stat::make('Gesamte Reichweite', number_format($totalReach, 0, ',', '.'))
                ->description('Follower insgesamt')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),

            Stat::make('⌀ Engagement', number_format($avgEngagement, 2, ',', '.').' %')
                ->description($growth30d !== null ? "Follower-Wachstum (30 T): {$growth30d} %" : 'Kein 30-Tage-Vergleich möglich')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('warning'),
        ];
    }

    private function followerGrowthLast30Days(): ?string
    {
        $now = ChannelMetric::query()
            ->whereDate('captured_at', '>=', now()->subDays(3))
            ->sum('followers');

        $old = ChannelMetric::query()
            ->whereDate('captured_at', '<=', now()->subDays(25))
            ->whereDate('captured_at', '>=', now()->subDays(35))
            ->sum('followers');

        if ($old <= 0) {
            return null;
        }

        $pct = (($now - $old) / $old) * 100;

        return ($pct >= 0 ? '+' : '').number_format($pct, 1, ',', '.');
    }
}
