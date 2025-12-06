<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete()->comment('Null = available for all branches');
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['retail', 'wholesale', 'member', 'promo', 'custom'])->default('retail');
            $table->decimal('min_order_amount', 15, 2)->nullable()->comment('Minimum order to apply this price list');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->integer('priority')->default(0)->comment('Higher priority takes precedence');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['branch_id', 'is_active']);
            $table->index(['valid_from', 'valid_until']);
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_lists');
    }
};
