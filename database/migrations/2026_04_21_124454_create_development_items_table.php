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
        Schema::create('development_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('idea');
            $table->text('description')->nullable();
            $table->json('materials')->nullable();
            $table->json('colors')->nullable();
            $table->json('sizes')->nullable();
            $table->decimal('target_price', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->date('deadline')->nullable();
            $table->timestamps();

            $table->index(['status', 'category_id']);
        });

        Schema::create('competitor_product_development_item', function (Blueprint $table) {
            $table->foreignId('development_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('competitor_product_id')->constrained()->cascadeOnDelete();
            $table->primary(['development_item_id', 'competitor_product_id'], 'dev_competitor_pk');
        });

        Schema::create('development_item_supplier_product', function (Blueprint $table) {
            $table->foreignId('development_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_product_id')->constrained()->cascadeOnDelete();
            $table->primary(['development_item_id', 'supplier_product_id'], 'dev_supplier_pk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('development_item_supplier_product');
        Schema::dropIfExists('competitor_product_development_item');
        Schema::dropIfExists('development_items');
    }
};
