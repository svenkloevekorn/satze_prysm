<?php

namespace Database\Seeders;

use App\Models\ChannelMetric;
use App\Models\Influencer;
use App\Models\SocialChannel;
use Illuminate\Database\Seeder;

class InfluencerSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            [
                'influencer' => [
                    'name' => 'Lisa Cyclista',
                    'country' => 'DE',
                    'bio' => 'Roadbike-Enthusiastin aus München. Fokus: Alpenpässe, Training, Gear-Reviews.',
                    'email' => 'lisa@cyclista.example',
                ],
                'channels' => [
                    ['platform' => 'instagram', 'handle' => 'lisacyclista', 'followers' => 48300, 'engagement_rate' => 4.8, 'url' => 'https://instagram.com/lisacyclista'],
                    ['platform' => 'youtube', 'handle' => 'LisaCyclista', 'followers' => 12000, 'engagement_rate' => 3.2, 'url' => 'https://youtube.com/@LisaCyclista'],
                ],
            ],
            [
                'influencer' => [
                    'name' => 'Marco Trailrunner',
                    'country' => 'IT',
                    'bio' => 'Trail- und Ultrarunner, Dolomiten-basiert. Content rund um Running-Gear.',
                    'email' => 'marco@trailrun.example',
                ],
                'channels' => [
                    ['platform' => 'instagram', 'handle' => 'marco.trail', 'followers' => 87500, 'engagement_rate' => 6.2, 'url' => 'https://instagram.com/marco.trail'],
                    ['platform' => 'tiktok', 'handle' => 'marcotrail', 'followers' => 156000, 'engagement_rate' => 8.4, 'url' => 'https://tiktok.com/@marcotrail'],
                ],
            ],
        ];

        foreach ($demo as $data) {
            $inf = Influencer::firstOrCreate(
                ['name' => $data['influencer']['name']],
                $data['influencer'] + ['is_active' => true],
            );

            foreach ($data['channels'] as $chanData) {
                $channel = SocialChannel::firstOrCreate(
                    [
                        'owner_type' => 'influencer',
                        'owner_id' => $inf->id,
                        'platform' => $chanData['platform'],
                    ],
                    $chanData + ['is_active' => true],
                );

                ChannelMetric::firstOrCreate(
                    ['social_channel_id' => $channel->id, 'captured_at' => now()->toDateString()],
                    [
                        'followers' => $chanData['followers'],
                        'posts_count' => fake()->numberBetween(100, 2000),
                        'avg_likes' => (int) ($chanData['followers'] * ($chanData['engagement_rate'] / 100)),
                        'avg_comments' => (int) ($chanData['followers'] * ($chanData['engagement_rate'] / 100) * 0.05),
                        'engagement_rate' => $chanData['engagement_rate'],
                    ],
                );
            }
        }
    }
}
