<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bundle_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 15, 2)->comment('Price per unit at time of bundle creation');
            $table->decimal('subtotal', 15, 2)->comment('quantity * unit_price');
            $table->timestamps();

            $table->unique(['bundle_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bundle_items');
    }
};
