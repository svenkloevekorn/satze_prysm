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
        Schema::create('channel_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_channel_id')->constrained()->cascadeOnDelete();
            $table->date('captured_at');
            $table->unsignedBigInteger('followers')->nullable();
            $table->unsignedInteger('posts_count')->nullable();
            $table->unsignedInteger('avg_likes')->nullable();
            $table->unsignedInteger('avg_comments')->nullable();
            $table->decimal('engagement_rate', 5, 2)->nullable();
            $table->timestamps();

            $table->index(['social_channel_id', 'captured_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channel_metrics');
    }
};
