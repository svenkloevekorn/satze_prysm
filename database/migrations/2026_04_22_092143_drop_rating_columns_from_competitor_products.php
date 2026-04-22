<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bestehende overall_rating/positives/negatives als Gesamt-Bewertung
        //    in die ratings-Tabelle uebernehmen, damit keine Daten verloren gehen.
        if (Schema::hasColumn('competitor_products', 'overall_rating')) {
            $admin = DB::table('users')->where('email', 'admin@admin.com')->first();
            $adminId = $admin?->id;

            DB::table('competitor_products')
                ->whereNotNull('overall_rating')
                ->orderBy('id')
                ->get()
                ->each(function ($product) use ($adminId) {
                    $existsGesamt = DB::table('ratings')
                        ->where('ratable_type', 'competitor_product')
                        ->where('ratable_id', $product->id)
                        ->whereNull('rating_dimension_id')
                        ->exists();

                    if (! $existsGesamt) {
                        DB::table('ratings')->insert([
                            'ratable_type' => 'competitor_product',
                            'ratable_id' => $product->id,
                            'rating_dimension_id' => null,
                            'user_id' => $adminId,
                            'sources' => json_encode(['product_worn']),
                            'score' => $product->overall_rating,
                            'comment' => 'Automatisch migriert aus alter Produkt-Bewertung',
                            'positives' => $product->positives,
                            'negatives' => $product->negatives,
                            'rated_at' => now()->toDateString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                });
        }

        // 2. Alte Spalten entfernen
        Schema::table('competitor_products', function (Blueprint $table) {
            $table->dropColumn(['overall_rating', 'positives', 'negatives']);
        });
    }

    public function down(): void
    {
        Schema::table('competitor_products', function (Blueprint $table) {
            $table->unsignedTinyInteger('overall_rating')->nullable()->after('price_max');
            $table->text('positives')->nullable()->after('overall_rating');
            $table->text('negatives')->nullable()->after('positives');
        });
    }
};
