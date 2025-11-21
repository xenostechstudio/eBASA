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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('full_name');
            $table->string('preferred_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nik')->unique();
            $table->string('npwp')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('employment_type')->default('full_time');
            $table->string('employment_class')->nullable();
            $table->string('work_mode')->nullable();
            $table->string('status')->default('active');
            $table->string('salary_band')->nullable();
            $table->date('start_date')->nullable();
            $table->date('probation_end_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_whatsapp')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
