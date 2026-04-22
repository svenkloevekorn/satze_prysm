<?php

namespace App\Filament\Imports;

use App\Models\ChannelMetric;
use App\Models\SocialChannel;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class ChannelMetricImporter extends Importer
{
    protected static ?string $model = ChannelMetric::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('channel_handle')
                ->label('Channel-Handle oder Platform:Handle (z.B. instagram:lisacyclista)')
                ->requiredMapping()
                ->fillRecordUsing(function (ChannelMetric $record, ?string $state) {
                    if (blank($state)) {
                        return;
                    }
                    $state = trim($state);
                    $channel = null;

                    if (str_contains($state, ':')) {
                        [$platform, $handle] = explode(':', $state, 2);
                        $channel = SocialChannel::where('platform', trim($platform))
                            ->where('handle', trim($handle))
                            ->first();
                    } else {
                        $channel = SocialChannel::where('handle', $state)->first();
                    }

                    if ($channel) {
                        $record->social_channel_id = $channel->id;
                    }
                }),
            ImportColumn::make('captured_at')
                ->label('Datum (YYYY-MM-DD)')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('followers')
                ->label('Follower')
                ->integer()
                ->rules(['nullable', 'integer']),
            ImportColumn::make('posts_count')
                ->label('Posts gesamt')
                ->integer()
                ->rules(['nullable', 'integer']),
            ImportColumn::make('avg_likes')
                ->label('⌀ Likes')
                ->integer()
                ->rules(['nullable', 'integer']),
            ImportColumn::make('avg_comments')
                ->label('⌀ Kommentare')
                ->integer()
                ->rules(['nullable', 'integer']),
            ImportColumn::make('engagement_rate')
                ->label('Engagement-Rate (%)')
                ->numeric()
                ->rules(['nullable', 'numeric']),
        ];
    }

    public function resolveRecord(): ChannelMetric
    {
        return new ChannelMetric;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import abgeschlossen: '.Number::format($import->successful_rows).' Zeile(n) erfolgreich.';
        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' fehlgeschlagen (z.B. unbekannter Channel).';
        }

        return $body;
    }
}
