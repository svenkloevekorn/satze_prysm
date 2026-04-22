<?php

namespace App\Filament\Widgets;

use App\Enums\SocialPlatform;
use App\Models\SocialChannel;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopChannelsWidget extends BaseWidget
{
    protected static ?string $heading = 'Top-Kanäle nach Engagement';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 7;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SocialChannel::query()
                    ->with('owner')
                    ->where('is_active', true)
                    ->whereNotNull('engagement_rate')
                    ->orderByDesc('engagement_rate')
                    ->limit(5),
            )
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Noch keine Kanäle mit Engagement-Daten')
            ->columns([
                TextColumn::make('platform')
                    ->label('Plattform')
                    ->badge()
                    ->formatStateUsing(fn (SocialPlatform $state) => $state->label())
                    ->color(fn (SocialPlatform $state) => $state->color())
                    ->icon(fn (SocialPlatform $state) => $state->icon()),
                TextColumn::make('owner.name')
                    ->label('Besitzer')
                    ->weight('bold'),
                TextColumn::make('handle')
                    ->label('Handle'),
                TextColumn::make('followers')
                    ->label('Follower')
                    ->numeric(locale: 'de')
                    ->sortable(),
                TextColumn::make('engagement_rate')
                    ->label('Engagement')
                    ->formatStateUsing(fn ($state) => $state ? number_format((float) $state, 2, ',', '.').' %' : '–')
                    ->color('success')
                    ->sortable(),
                TextColumn::make('relevance_score')
                    ->label('Relevanz')
                    ->state(fn (SocialChannel $record) => $this->relevanceScore($record))
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 80 => 'success',
                        $state >= 50 => 'warning',
                        default => 'gray',
                    }),
            ]);
    }

    /**
     * Relevanz-Score: 0-100.
     * Setzt sich zusammen aus Engagement-Rate (max 8% = 100%)
     * und Follower-Größe (log-skaliert, max 500k = 100%).
     */
    protected function relevanceScore(SocialChannel $channel): int
    {
        $engagementScore = min(100, ((float) ($channel->engagement_rate ?? 0) / 8) * 100);
        $followers = max(1, (int) ($channel->followers ?? 0));
        $followerScore = min(100, (log10($followers) / log10(500000)) * 100);

        return (int) round(($engagementScore * 0.6) + ($followerScore * 0.4));
    }
}
