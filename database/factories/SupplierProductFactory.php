<?php

namespace Database\Factories;

use App\Models\SupplierProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupplierProduct>
 */
class SupplierProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ek = fake()->randomFloat(2, 5, 50);

        return [
            'supplier_id' => \App\Models\Supplier::factory(),
            'category_id' => \App\Models\Category::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'purchase_price' => $ek,
            'recommended_retail_price' => $ek * fake()->randomFloat(1, 2, 3.5),
            'moq' => fake()->randomElement([50, 100, 200, 500]),
            'materials' => fake()->randomElements(['Polyester', 'Nylon', 'Baumwolle', 'Lycra'], 2),
            'colors' => fake()->randomElements(['Schwarz', 'Weiß', 'Blau'], 2),
            'sizes' => ['S', 'M', 'L', 'XL'],
        ];
    }
}
