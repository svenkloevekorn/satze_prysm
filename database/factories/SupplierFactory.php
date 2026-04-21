<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'country' => fake()->randomElement(['CN', 'TR', 'IT', 'DE', 'PT']),
            'address' => fake()->address(),
            'rating' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}
