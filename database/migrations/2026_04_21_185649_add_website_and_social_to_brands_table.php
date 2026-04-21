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
        Schema::table('brands', function (Blueprint $table) {
            $table->string('website')->nullable()->after('description');
            $table->string('country', 2)->nullable()->after('website');
            $table->string('instagram')->nullable()->after('country');
            $table->string('facebook')->nullable()->after('instagram');
            $table->string('linkedin')->nullable()->after('facebook');
            $table->string('tiktok')->nullable()->after('linkedin');
            $table->string('youtube')->nullable()->after('tiktok');
        });
    }

    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn(['website', 'country', 'instagram', 'facebook', 'linkedin', 'tiktok', 'youtube']);
        });
    }
};
