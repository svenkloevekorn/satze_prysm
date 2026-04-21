<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CompetitorProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompetitorProduct>
 */
class CompetitorProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $min = fake()->numberBetween(20, 80);

        return [
            'name' => fake()->words(3, true),
            'brand_id' => Brand::factory(),
            'category_id' => Category::factory(),
            'description' => fake()->paragraph(),
            'materials' => fake()->randomElements(['Polyester', 'Nylon', 'Merino', 'Lycra', 'Baumwolle'], 2),
            'colors' => fake()->randomElements(['Schwarz', 'Weiß', 'Rot', 'Blau', 'Grün'], 2),
            'sizes' => ['S', 'M', 'L', 'XL'],
            'price_min' => $min,
            'price_max' => $min + fake()->numberBetween(10, 50),
            'overall_rating' => fake()->numberBetween(1, 10),
            'positives' => fake()->sentence(),
            'negatives' => fake()->sentence(),
        ];
    }
}
