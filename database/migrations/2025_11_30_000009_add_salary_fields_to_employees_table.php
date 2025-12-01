<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('base_salary', 15, 2)->default(0)->after('salary_band');
            $table->foreignId('payroll_group_id')->nullable()->after('base_salary')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['payroll_group_id']);
            $table->dropColumn(['base_salary', 'payroll_group_id']);
        });
    }
};
