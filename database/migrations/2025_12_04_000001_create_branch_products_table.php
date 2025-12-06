<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('selling_price', 15, 2)->nullable()->comment('Branch-specific price, null = use master price');
            $table->decimal('cost_price', 15, 2)->nullable()->comment('Branch-specific cost, null = use master cost');
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock_level')->default(0);
            $table->integer('max_stock_level')->nullable();
            $table->boolean('is_available')->default(true)->comment('Whether product is available at this branch');
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['branch_id', 'product_id']);
            $table->index(['branch_id', 'is_available']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_products');
    }
};
