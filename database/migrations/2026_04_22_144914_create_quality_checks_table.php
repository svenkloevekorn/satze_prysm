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
        Schema::create('quality_checks', function (Blueprint $table) {
            $table->id();
            $table->morphs('checkable'); // checkable_type + checkable_id
            $table->foreignId('quality_criterion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('pending');
            $table->text('comment')->nullable();
            $table->date('checked_at')->nullable();
            $table->timestamps();

            $table->unique(['checkable_type', 'checkable_id', 'quality_criterion_id'], 'quality_checks_unique');
            $table->index(['status', 'quality_criterion_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_checks');
    }
};
