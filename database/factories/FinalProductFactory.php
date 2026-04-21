<?php

namespace Database\Factories;

use App\Models\FinalProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FinalProduct>
 */
class FinalProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cost = fake()->randomFloat(2, 15, 80);

        return [
            'category_id' => \App\Models\Category::factory(),
            'name' => fake()->words(3, true),
            'sku' => 'STZ-'.strtoupper(fake()->unique()->bothify('??###')),
            'description' => fake()->sentence(),
            'cost_price' => $cost,
            'retail_price' => $cost * fake()->randomFloat(1, 2, 3),
            'launched_at' => fake()->dateTimeBetween('-6 months'),
        ];
    }
}
