<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retail_products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('barcode')->nullable()->index();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('retail_product_categories')->nullOnDelete();
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->string('unit')->default('pcs');
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock_level')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('track_inventory')->default(true);
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retail_products');
    }
};
