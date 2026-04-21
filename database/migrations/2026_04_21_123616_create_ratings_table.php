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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->morphs('ratable'); // ratable_type + ratable_id
            $table->foreignId('rating_dimension_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->default('internal'); // internal | external
            $table->unsignedTinyInteger('score');
            $table->text('comment')->nullable();
            $table->text('positives')->nullable();
            $table->text('negatives')->nullable();
            $table->date('rated_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'rating_dimension_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
