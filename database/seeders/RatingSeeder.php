<?php

namespace Database\Seeders;

use App\Models\CompetitorProduct;
use App\Models\Rating;
use App\Models\RatingDimension;
use App\Models\SupplierProduct;
use App\Models\User;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')->first();
        $adminId = $admin?->id;

        $design = RatingDimension::where('name', 'Design')->first();
        $material = RatingDimension::where('name', 'Material')->first();
        $performance = RatingDimension::where('name', 'Performance')->first();

        $castelli = CompetitorProduct::where('name', 'like', 'Castelli%')->first();
        $rapha = CompetitorProduct::where('name', 'like', 'Rapha%')->first();

        if ($castelli) {
            Rating::firstOrCreate(
                ['ratable_type' => 'competitor_product', 'ratable_id' => $castelli->id, 'rating_dimension_id' => $design?->id],
                ['user_id' => $adminId, 'sources' => ['product_worn'], 'score' => 8, 'comment' => 'Schönes, klares Design', 'rated_at' => now()->subWeeks(1)],
            );
            Rating::firstOrCreate(
                ['ratable_type' => 'competitor_product', 'ratable_id' => $castelli->id, 'rating_dimension_id' => $material?->id],
                ['user_id' => $adminId, 'sources' => ['product_worn', 'product_ordered'], 'score' => 9, 'comment' => 'Sehr atmungsaktiv', 'rated_at' => now()->subWeeks(1)],
            );
            Rating::firstOrCreate(
                ['ratable_type' => 'competitor_product', 'ratable_id' => $castelli->id, 'rating_dimension_id' => null],
                ['user_id' => $adminId, 'sources' => ['forum_posts', 'product_seen_online'], 'score' => 8, 'comment' => 'Durchschnitt aus 50 Online-Reviews', 'positives' => 'Komfort', 'negatives' => 'Preis', 'rated_at' => now()->subDays(10)],
            );
        }

        if ($rapha) {
            Rating::firstOrCreate(
                ['ratable_type' => 'competitor_product', 'ratable_id' => $rapha->id, 'rating_dimension_id' => $performance?->id],
                ['user_id' => $adminId, 'sources' => ['product_worn', 'story'], 'score' => 10, 'comment' => 'Top Performance auf langen Touren', 'rated_at' => now()->subDays(3)],
            );
        }

        $supplierProduct = SupplierProduct::first();
        if ($supplierProduct) {
            Rating::firstOrCreate(
                ['ratable_type' => 'supplier_product', 'ratable_id' => $supplierProduct->id, 'rating_dimension_id' => $material?->id],
                ['user_id' => $adminId, 'sources' => ['product_ordered'], 'score' => 7, 'comment' => 'Solides Material, kein Premium', 'rated_at' => now()->subDays(5)],
            );
        }
    }
}
