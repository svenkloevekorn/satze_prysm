<?php

namespace Database\Factories;

use App\Models\DevelopmentItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DevelopmentItem>
 */
class DevelopmentItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'category_id' => \App\Models\Category::factory(),
            'user_id' => \App\Models\User::factory(),
            'status' => fake()->randomElement(['idea', 'in_progress', 'concept_confirmed', 'tech_sheet_created', 'sample_received']),
            'description' => fake()->paragraph(),
            'materials' => fake()->randomElements(['Polyester', 'Lycra', 'Nylon'], 2),
            'colors' => ['Schwarz', 'Weiß'],
            'sizes' => ['S', 'M', 'L'],
            'target_price' => fake()->randomFloat(2, 50, 200),
            'deadline' => fake()->dateTimeBetween('now', '+3 months'),
        ];
    }
}
