<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 50)->unique();
            $table->foreignId('purchase_order_id')->constrained()->restrictOnDelete();
            $table->foreignId('warehouse_id')->constrained()->restrictOnDelete();
            $table->date('received_date');
            $table->string('status', 30)->default('draft');
            // draft, completed, cancelled
            $table->string('received_by_name')->nullable();
            $table->string('delivery_note_number')->nullable()->comment('Supplier delivery note/invoice');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('purchase_order_id');
            $table->index('received_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_receipts');
    }
};
