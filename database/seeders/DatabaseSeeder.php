<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            RatingDimensionSeeder::class,
            ShopSeeder::class,
            QualityCriterionSeeder::class,
            CompetitorProductSeeder::class,
            SupplierSeeder::class,
            RatingSeeder::class,
            DevelopmentItemSeeder::class,
            InfluencerSeeder::class,
            PlaceholderImagesSeeder::class,
        ]);
    }
}
