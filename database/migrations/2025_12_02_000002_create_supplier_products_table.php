<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pivot table: which products each supplier can provide
        Schema::create('supplier_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('supplier_price', 15, 2)->nullable()->comment('Supplier\'s price for this product');
            $table->string('supplier_sku', 100)->nullable()->comment('Supplier\'s own SKU');
            $table->unsignedSmallInteger('lead_time_days')->nullable()->comment('Typical delivery days');
            $table->unsignedInteger('min_order_qty')->default(1);
            $table->boolean('is_preferred')->default(false)->comment('Preferred supplier for this product');
            $table->timestamps();

            $table->unique(['supplier_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_products');
    }
};
