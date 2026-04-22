<?php

namespace Database\Factories;

use App\Models\ChannelMetric;
use App\Models\SocialChannel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChannelMetric>
 */
class ChannelMetricFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'social_channel_id' => SocialChannel::factory(),
            'captured_at' => fake()->dateTimeBetween('-3 months'),
            'followers' => fake()->numberBetween(1000, 500000),
            'posts_count' => fake()->numberBetween(10, 5000),
            'avg_likes' => fake()->numberBetween(100, 50000),
            'avg_comments' => fake()->numberBetween(10, 500),
            'engagement_rate' => fake()->randomFloat(2, 0.5, 12),
        ];
    }
}
