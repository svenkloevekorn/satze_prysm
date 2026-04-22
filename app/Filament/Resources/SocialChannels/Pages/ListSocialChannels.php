<?php

namespace App\Filament\Resources\SocialChannels\Pages;

use App\Filament\Imports\ChannelMetricImporter;
use App\Filament\Resources\SocialChannels\SocialChannelResource;
use App\Models\ChannelMetric;
use App\Models\SocialChannel;
use App\Services\SocialMetrics\SimulatedFetcher;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListSocialChannels extends ListRecords
{
    protected static string $resource = SocialChannelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('fetchNow')
                ->label('Snapshots jetzt holen')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Metrics-Snapshots anlegen')
                ->modalDescription('Erzeugt heute einen Snapshot für jeden aktiven Kanal (Demo-Simulation). Pro Tag nur einmal pro Kanal.')
                ->action(function () {
                    $fetcher = new SimulatedFetcher;
                    $today = now()->toDateString();
                    $created = 0;

                    SocialChannel::where('is_active', true)->get()->each(function ($channel) use ($fetcher, $today, &$created) {
                        if (ChannelMetric::where('social_channel_id', $channel->id)
                            ->where('captured_at', $today)->exists()) {
                            return;
                        }
                        $data = $fetcher->fetch($channel);
                        if ($data === null) {
                            return;
                        }
                        ChannelMetric::create(['social_channel_id' => $channel->id, 'captured_at' => $today, ...$data]);
                        $channel->update(['followers' => $data['followers'], 'engagement_rate' => $data['engagement_rate']]);
                        $created++;
                    });

                    Notification::make()->title("{$created} Snapshots angelegt")->success()->send();
                }),
            ImportAction::make()
                ->label('Metrics-CSV importieren')
                ->importer(ChannelMetricImporter::class),
            CreateAction::make()->label('Neuer Kanal'),
        ];
    }
}
