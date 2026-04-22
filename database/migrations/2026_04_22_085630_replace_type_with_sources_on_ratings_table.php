<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Neue Spalte "sources" als JSON-Array (nullable)
        Schema::table('ratings', function (Blueprint $table) {
            $table->json('sources')->nullable()->after('type');
        });

        // 2. Bestehende Daten konvertieren:
        //    internal → ['product_worn'] (eigene Erfahrung)
        //    external → ['forum_posts'] (fremde Quelle)
        DB::table('ratings')->where('type', 'internal')->update([
            'sources' => json_encode(['product_worn']),
        ]);
        DB::table('ratings')->where('type', 'external')->update([
            'sources' => json_encode(['forum_posts']),
        ]);

        // 3. Alte type-Spalte + zugehoerigen Index entfernen
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropIndex(['type', 'rating_dimension_id']);
            $table->dropColumn('type');
        });

        // 4. Neuen Index auf rating_dimension_id allein
        Schema::table('ratings', function (Blueprint $table) {
            $table->index('rating_dimension_id');
        });
    }

    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropIndex(['rating_dimension_id']);
            $table->string('type')->default('internal')->after('user_id');
            $table->index(['type', 'rating_dimension_id']);
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->dropColumn('sources');
        });
    }
};
