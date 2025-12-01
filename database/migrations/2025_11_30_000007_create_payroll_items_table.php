<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['earning', 'deduction'])->default('earning');
            $table->enum('category', [
                'basic_salary',
                'allowance',
                'bonus',
                'overtime',
                'thr',
                'bpjs_kesehatan',
                'bpjs_ketenagakerjaan',
                'pph21',
                'loan',
                'other_earning',
                'other_deduction',
            ])->default('allowance');
            $table->enum('calculation_type', ['fixed', 'percentage', 'formula'])->default('fixed');
            $table->decimal('default_amount', 15, 2)->default(0);
            $table->decimal('percentage_base', 5, 2)->nullable()->comment('Percentage of base salary');
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_recurring')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
    }
};
