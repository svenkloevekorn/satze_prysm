<?php

namespace Database\Factories;

use App\Models\RatingDimension;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RatingDimension>
 */
class RatingDimensionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->word()),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(1, 100),
            'is_active' => true,
        ];
    }
}
