<?php

namespace Database\Seeders;

use App\Models\RatingDimension;
use Illuminate\Database\Seeder;

class RatingDimensionSeeder extends Seeder
{
    public function run(): void
    {
        $dimensions = [
            'Design',
            'Material',
            'Verarbeitung',
            'Performance',
            'Preis-Leistung',
        ];

        foreach ($dimensions as $index => $name) {
            RatingDimension::updateOrCreate(
                ['name' => $name],
                [
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
