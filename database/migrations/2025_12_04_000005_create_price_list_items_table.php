<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_list_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 15, 2);
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->nullable();
            $table->integer('min_qty')->default(1)->comment('Minimum quantity to get this price');
            $table->timestamps();

            $table->unique(['price_list_id', 'product_id', 'min_qty']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_list_items');
    }
};
