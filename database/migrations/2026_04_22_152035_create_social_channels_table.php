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
        Schema::create('social_channels', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('owner'); // owner_type + owner_id: Influencer oder Brand
            $table->string('platform');
            $table->string('handle')->nullable();
            $table->string('url')->nullable();
            $table->unsignedBigInteger('followers')->nullable();
            $table->decimal('engagement_rate', 5, 2)->nullable(); // 0.00 - 100.00 %
            $table->string('language', 5)->nullable();
            $table->string('country', 2)->nullable();
            $table->json('categories')->nullable(); // thematische Tags
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_channels');
    }
};
