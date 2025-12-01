<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollPayout extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'code',
        'payroll_run_id',
        'employee_id',
        'gross_salary',
        'total_allowances',
        'total_deductions',
        'net_salary',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'status',
        'paid_at',
        'payment_reference',
        'notes',
    ];

    protected $casts = [
        'gross_salary' => 'decimal:2',
        'total_allowances' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
