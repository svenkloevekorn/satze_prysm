<?php

namespace Database\Factories;

use App\Models\Influencer;
use App\Models\SocialChannel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SocialChannel>
 */
class SocialChannelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_type' => 'influencer',
            'owner_id' => Influencer::factory(),
            'platform' => fake()->randomElement(['instagram', 'tiktok', 'youtube', 'x', 'linkedin', 'facebook']),
            'handle' => fake()->userName(),
            'url' => fake()->url(),
            'followers' => fake()->numberBetween(1000, 500000),
            'engagement_rate' => fake()->randomFloat(2, 0.5, 12),
            'country' => fake()->randomElement(['DE', 'IT', 'US', 'GB']),
            'is_active' => true,
        ];
    }
}
