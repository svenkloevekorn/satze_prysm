<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\QualityCriterion;
use Illuminate\Database\Seeder;

class QualityCriterionSeeder extends Seeder
{
    public function run(): void
    {
        $criteria = [
            'Nahtqualität' => ['Cycling Jerseys', 'Bib Shorts', 'Running Shirts', 'Running Shorts', 'Jackets', 'T-Shirts'],
            'Atmungsaktivität' => ['Cycling Jerseys', 'Running Shirts', 'Jackets', 'T-Shirts'],
            'Passform' => ['Cycling Jerseys', 'Bib Shorts', 'Running Shirts', 'Running Shorts', 'Jackets', 'T-Shirts'],
            'Wasserdichtigkeit' => ['Jackets', 'Bags'],
            'Polsterung' => ['Bib Shorts'],
            'Reflektoren' => ['Cycling Jerseys', 'Running Shirts', 'Jackets'],
        ];

        foreach ($criteria as $name => $categoryNames) {
            $criterion = QualityCriterion::updateOrCreate(
                ['name' => $name],
                ['is_active' => true],
            );

            $categoryIds = Category::whereIn('name', $categoryNames)->pluck('id');
            $criterion->categories()->sync($categoryIds);
        }
    }
}
