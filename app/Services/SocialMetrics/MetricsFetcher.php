<?php

namespace App\Services\SocialMetrics;

use App\Models\SocialChannel;

/**
 * Interface für Plattform-spezifische Metrics-Fetcher.
 * Implementierungen später: InstagramFetcher, TikTokFetcher, YouTubeFetcher ...
 *
 * Aktuell: SimulatedFetcher (für Demo-Zwecke).
 */
interface MetricsFetcher
{
    /**
     * Holt aktuelle Metrics für einen Kanal.
     * Gibt null zurück, wenn der Kanal nicht unterstützt wird oder der Abruf fehlschlägt.
     *
     * @return array{followers: int, posts_count: ?int, avg_likes: ?int, avg_comments: ?int, engagement_rate: ?float}|null
     */
    public function fetch(SocialChannel $channel): ?array;

    /**
     * Menschenlesbarer Name (für Logs / Notifications).
     */
    public function name(): string;
}
