<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PayrollItem extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'category',
        'calculation_type',
        'default_amount',
        'percentage_base',
        'is_taxable',
        'is_recurring',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'percentage_base' => 'decimal:2',
        'is_taxable' => 'boolean',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public const TYPES = ['earning', 'deduction'];

    // Note: Base Salary (Gaji Pokok) is stored directly on Employee.base_salary, not as a PayrollItem
    public const CATEGORIES = [
        'allowance' => 'Tunjangan',
        'bonus' => 'Bonus',
        'overtime' => 'Lembur',
        'thr' => 'THR',
        'bpjs_kesehatan' => 'BPJS Kesehatan',
        'bpjs_ketenagakerjaan' => 'BPJS Ketenagakerjaan',
        'pph21' => 'PPh 21',
        'loan' => 'Pinjaman',
        'other_earning' => 'Pendapatan Lainnya',
        'other_deduction' => 'Potongan Lainnya',
    ];

    public const CALCULATION_TYPES = ['fixed', 'percentage', 'formula'];

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_payroll_items')
            ->withPivot(['amount', 'effective_date', 'end_date', 'is_active', 'notes'])
            ->withTimestamps();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }
}
