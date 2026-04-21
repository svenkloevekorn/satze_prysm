<?php

namespace Database\Factories;

use App\Models\ProductShopEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductShopEntry>
 */
class ProductShopEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'competitor_product_id' => \App\Models\CompetitorProduct::factory(),
            'shop_id' => \App\Models\Shop::factory(),
            'product_url' => fake()->url(),
            'observed_price' => fake()->randomFloat(2, 20, 200),
            'observed_at' => fake()->dateTimeBetween('-3 months'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
