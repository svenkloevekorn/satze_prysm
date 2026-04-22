<?php

namespace Database\Factories;

use App\Models\Influencer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Influencer>
 */
class InfluencerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'country' => fake()->randomElement(['DE', 'IT', 'US', 'GB', 'FR']),
            'bio' => fake()->sentence(),
            'email' => fake()->unique()->safeEmail(),
            'is_active' => true,
        ];
    }
}
