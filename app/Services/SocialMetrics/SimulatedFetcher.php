<?php

namespace App\Services\SocialMetrics;

use App\Models\SocialChannel;

/**
 * Demo-Fetcher: nimmt den letzten Snapshot als Basis und variiert die
 * Werte leicht (±2 % Follower, ±10 % Engagement). Wenn kein Snapshot
 * existiert, werden die Channel-Felder als Basis genutzt.
 *
 * Nicht fuer Produktion gedacht – spaeter durch echte API-Fetcher ersetzen.
 */
class SimulatedFetcher implements MetricsFetcher
{
    public function fetch(SocialChannel $channel): ?array
    {
        $latest = $channel->metrics()->latest('captured_at')->first();

        $baseFollowers = $latest->followers ?? $channel->followers ?? 10000;
        $baseEngagement = $latest->engagement_rate ?? $channel->engagement_rate ?? 3.0;
        $basePosts = $latest->posts_count ?? 500;

        $followers = (int) round($baseFollowers * random_int(98, 102) / 100);
        $engagement = round($baseEngagement * (random_int(90, 110) / 100), 2);
        $posts = $basePosts + random_int(0, 5);
        $avgLikes = (int) round($followers * ($engagement / 100));

        return [
            'followers' => $followers,
            'posts_count' => $posts,
            'avg_likes' => $avgLikes,
            'avg_comments' => (int) round($avgLikes * 0.05),
            'engagement_rate' => $engagement,
        ];
    }

    public function name(): string
    {
        return 'Simulated (Demo)';
    }
}
