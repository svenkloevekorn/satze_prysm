<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $shops = [
            ['name' => 'Amazon DE', 'url' => 'https://www.amazon.de', 'country' => 'DE'],
            ['name' => 'Bike-Discount', 'url' => 'https://www.bike-discount.de', 'country' => 'DE'],
            ['name' => 'Wiggle', 'url' => 'https://www.wiggle.com', 'country' => 'GB'],
            ['name' => 'SportScheck', 'url' => 'https://www.sportscheck.com', 'country' => 'DE'],
        ];

        foreach ($shops as $shop) {
            Shop::updateOrCreate(['name' => $shop['name']], $shop + ['is_active' => true]);
        }
    }
}
