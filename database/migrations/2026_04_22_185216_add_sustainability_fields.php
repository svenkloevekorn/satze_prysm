<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (['competitor_products', 'supplier_products', 'final_products', 'development_items'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->decimal('co2_kg', 6, 2)->nullable();
                $t->unsignedTinyInteger('recycled_content_pct')->nullable();
                $t->json('certifications')->nullable();
            });
        }

        Schema::table('suppliers', function (Blueprint $t) {
            $t->unsignedTinyInteger('sustainability_score')->nullable();
            $t->json('certifications')->nullable();
        });
    }

    public function down(): void
    {
        foreach (['competitor_products', 'supplier_products', 'final_products', 'development_items'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn(['co2_kg', 'recycled_content_pct', 'certifications']);
            });
        }

        Schema::table('suppliers', function (Blueprint $t) {
            $t->dropColumn(['sustainability_score', 'certifications']);
        });
    }
};
