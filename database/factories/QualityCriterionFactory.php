<?php

namespace Database\Factories;

use App\Models\QualityCriterion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QualityCriterion>
 */
class QualityCriterionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->words(2, true)),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
