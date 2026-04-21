<?php

namespace Database\Factories;

use App\Models\CompetitorProduct;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ratable_type' => 'competitor_product',
            'ratable_id' => CompetitorProduct::factory(),
            'rating_dimension_id' => null,
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['internal', 'external']),
            'score' => fake()->numberBetween(1, 10),
            'comment' => fake()->sentence(),
            'positives' => fake()->sentence(),
            'negatives' => fake()->sentence(),
            'rated_at' => fake()->dateTimeBetween('-3 months'),
        ];
    }
}
