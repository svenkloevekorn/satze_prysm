<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CompetitorProduct;
use App\Models\DevelopmentItem;
use App\Models\SupplierProduct;
use App\Models\User;
use Illuminate\Database\Seeder;

class DevelopmentItemSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')->first();
        $jerseys = Category::where('name', 'Cycling Jerseys')->first();
        $bibs = Category::where('name', 'Bib Shorts')->first();

        $castelli = CompetitorProduct::where('name', 'like', 'Castelli%')->first();
        $rapha = CompetitorProduct::where('name', 'like', 'Rapha%')->first();
        $supplierJersey = SupplierProduct::where('name', 'like', 'Pro Summer Jersey%')->first();
        $supplierBib = SupplierProduct::where('name', 'like', 'Race Bib%')->first();

        $item1 = DevelopmentItem::firstOrCreate(
            ['name' => 'Staeze Pro Summer Jersey v1'],
            [
                'category_id' => $jerseys?->id,
                'user_id' => $admin?->id,
                'status' => 'in_progress',
                'description' => 'Leichtes Sommer-Trikot für die Staeze Pro-Linie. Inspiriert vom Castelli Climber.',
                'materials' => ['Polyester', 'Lycra'],
                'colors' => ['Schwarz', 'Weiß'],
                'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                'target_price' => 79.00,
                'deadline' => now()->addMonths(2),
                'notes' => 'Schnitt leicht taillierter als Castelli',
            ],
        );
        if ($castelli) {
            $item1->competitorInspirations()->syncWithoutDetaching([$castelli->id]);
        }
        if ($supplierJersey) {
            $item1->supplierBasis()->syncWithoutDetaching([$supplierJersey->id]);
        }

        $item2 = DevelopmentItem::firstOrCreate(
            ['name' => 'Staeze Race Bib 2026'],
            [
                'category_id' => $bibs?->id,
                'user_id' => $admin?->id,
                'status' => 'tech_sheet_created',
                'description' => 'Race-Bib für Wettkämpfe, inspired by Rapha Pro Team.',
                'materials' => ['Nylon', 'Lycra'],
                'colors' => ['Schwarz'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'target_price' => 149.00,
                'deadline' => now()->addMonths(4),
            ],
        );
        if ($rapha) {
            $item2->competitorInspirations()->syncWithoutDetaching([$rapha->id]);
        }
        if ($supplierBib) {
            $item2->supplierBasis()->syncWithoutDetaching([$supplierBib->id]);
        }

        $item3 = DevelopmentItem::firstOrCreate(
            ['name' => 'Staeze Daily Tee'],
            [
                'category_id' => Category::where('name', 'T-Shirts')->first()?->id,
                'user_id' => $admin?->id,
                'status' => 'idea',
                'description' => 'Erste Idee für ein Alltags-Shirt in der Marke.',
                'deadline' => now()->addMonths(6),
            ],
        );
    }
}
