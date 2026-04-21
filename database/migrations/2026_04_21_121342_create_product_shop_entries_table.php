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
        Schema::create('product_shop_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->string('product_url')->nullable();
            $table->decimal('observed_price', 10, 2)->nullable();
            $table->date('observed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['competitor_product_id', 'shop_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_shop_entries');
    }
};
