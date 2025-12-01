<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'code',
        'full_name',
        'preferred_name',
        'email',
        'phone',
        'whatsapp_number',
        'date_of_birth',
        'nik',
        'npwp',
        'branch_id',
        'department_id',
        'position_id',
        'manager_id',
        'employment_type',
        'employment_class',
        'work_mode',
        'status',
        'salary_band',
        'base_salary',
        'payroll_group_id',
        'start_date',
        'probation_end_date',
        'end_date',
        'emergency_contact_name',
        'emergency_contact_whatsapp',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'address',
        'notes',
        'meta',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'start_date' => 'date',
        'probation_end_date' => 'date',
        'end_date' => 'date',
        'base_salary' => 'decimal:2',
        'meta' => 'array',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(self::class, 'manager_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(self::class, 'manager_id');
    }

    public function payrollGroup(): BelongsTo
    {
        return $this->belongsTo(PayrollGroup::class);
    }

    public function payrollItems(): BelongsToMany
    {
        return $this->belongsToMany(PayrollItem::class, 'employee_payroll_items')
            ->withPivot(['amount', 'effective_date', 'end_date', 'is_active', 'notes'])
            ->withTimestamps();
    }

    public function employeePayrollItems(): HasMany
    {
        return $this->hasMany(EmployeePayrollItem::class);
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
