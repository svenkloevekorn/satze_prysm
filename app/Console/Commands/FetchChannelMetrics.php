<?php

namespace App\Console\Commands;

use App\Models\ChannelMetric;
use App\Models\SocialChannel;
use App\Services\SocialMetrics\SimulatedFetcher;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('staeze:fetch-channel-metrics {--channel= : ID eines einzelnen Kanals}')]
#[Description('Legt fuer aktive Social-Kanaele neue Metrics-Snapshots an (aktuell Demo-Simulation).')]
class FetchChannelMetrics extends Command
{
    public function handle(): int
    {
        $fetcher = new SimulatedFetcher;
        $this->info("Fetcher: {$fetcher->name()}");

        $query = SocialChannel::query()->where('is_active', true);
        if ($id = $this->option('channel')) {
            $query->whereKey($id);
        }

        $channels = $query->get();

        if ($channels->isEmpty()) {
            $this->warn('Keine aktiven Kanäle gefunden.');

            return self::SUCCESS;
        }

        $today = now()->toDateString();
        $created = 0;
        $skipped = 0;

        foreach ($channels as $channel) {
            // Nicht mehrfach pro Tag
            $exists = ChannelMetric::where('social_channel_id', $channel->id)
                ->whereDate('captured_at', $today)
                ->exists();

            if ($exists) {
                $skipped++;

                continue;
            }

            $data = $fetcher->fetch($channel);

            if ($data === null) {
                $skipped++;

                continue;
            }

            ChannelMetric::create([
                'social_channel_id' => $channel->id,
                'captured_at' => $today,
                ...$data,
            ]);

            // Channel-Stamm aktualisieren (für Liste / Ranking)
            $channel->update([
                'followers' => $data['followers'],
                'engagement_rate' => $data['engagement_rate'],
            ]);

            $created++;
        }

        $this->info("✓ {$created} Snapshots erstellt · {$skipped} übersprungen");

        return self::SUCCESS;
    }
}
