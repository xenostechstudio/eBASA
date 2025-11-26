<?php

namespace App\Livewire\HumanResource\Employees;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Support\HumanResourceNavigation;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal')]
class Edit extends Component
{
    public Employee $employee;

    /**
     * @var array<string, mixed>
     */
    public array $form = [];

    public array $branches = [];
    public array $departments = [];
    public array $positions = [];
    public array $managers = [];

    public array $employmentTypes = ['full_time', 'part_time', 'contract'];
    public array $employmentClasses = ['permanent', 'probation', 'seasonal'];
    public array $workModes = ['onsite', 'hybrid', 'remote'];
    public array $statuses = ['active', 'on_leave', 'probation', 'terminated'];

    public function mount(Employee $employee): void
    {
        $this->employee = $employee;

        $this->form = [
            'full_name' => $employee->full_name,
            'preferred_name' => $employee->preferred_name,
            'code' => $employee->code,
            'email' => $employee->email,
            'phone' => $employee->phone,
            'whatsapp_number' => $employee->whatsapp_number,
            'date_of_birth' => $employee->date_of_birth?->format('Y-m-d'),
            'nik' => $employee->nik,
            'npwp' => $employee->npwp,
            'address' => $employee->address,
            'branch_id' => $employee->branch_id,
            'department_id' => $employee->department_id,
            'position_id' => $employee->position_id,
            'manager_id' => $employee->manager_id,
            'employment_type' => $employee->employment_type,
            'employment_class' => $employee->employment_class,
            'work_mode' => $employee->work_mode,
            'status' => $employee->status,
            'salary_band' => $employee->salary_band,
            'start_date' => $employee->start_date?->format('Y-m-d'),
            'probation_end_date' => $employee->probation_end_date?->format('Y-m-d'),
            'emergency_contact_name' => $employee->emergency_contact_name,
            'emergency_contact_whatsapp' => $employee->emergency_contact_whatsapp,
            'bank_name' => $employee->bank_name,
            'bank_account_number' => $employee->bank_account_number,
            'bank_account_name' => $employee->bank_account_name,
            'notes' => $employee->notes,
        ];

        $this->branches = Branch::orderBy('name')->get(['id', 'name'])->all();
        $this->departments = Department::orderBy('name')->get(['id', 'name'])->all();
        $this->positions = Position::orderBy('title')->get(['id', 'title'])->all();
        $this->managers = Employee::where('id', '!=', $employee->id)
            ->orderBy('full_name')
            ->get(['id', 'full_name', 'code'])
            ->all();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes());

        $this->employee->update($validated['form']);

        session()->flash('status', 'Employee updated successfully');

        $this->dispatch('notify', message: 'Employee updated');

        $this->redirect(route('hr.employees'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.full_name' => ['required', 'string', 'max:255'],
            'form.preferred_name' => ['nullable', 'string', 'max:255'],
            'form.code' => ['required', 'string', 'max:50', Rule::unique('employees', 'code')->ignore($this->employee->id)],
            'form.email' => ['required', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($this->employee->id)],
            'form.phone' => ['nullable', 'string', 'max:50'],
            'form.whatsapp_number' => ['nullable', 'string', 'max:50'],
            'form.date_of_birth' => ['nullable', 'date'],
            'form.nik' => ['required', 'string', 'max:32', Rule::unique('employees', 'nik')->ignore($this->employee->id)],
            'form.npwp' => ['nullable', 'string', 'max:32'],
            'form.address' => ['nullable', 'string'],
            'form.branch_id' => ['required', 'integer', 'exists:branches,id'],
            'form.department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'form.position_id' => ['nullable', 'integer', 'exists:positions,id'],
            'form.manager_id' => ['nullable', 'integer', 'exists:employees,id'],
            'form.employment_type' => ['required', Rule::in($this->employmentTypes)],
            'form.employment_class' => ['nullable', Rule::in($this->employmentClasses)],
            'form.work_mode' => ['nullable', Rule::in($this->workModes)],
            'form.status' => ['required', Rule::in($this->statuses)],
            'form.salary_band' => ['nullable', 'string', 'max:50'],
            'form.start_date' => ['required', 'date'],
            'form.probation_end_date' => ['nullable', 'date', 'after_or_equal:form.start_date'],
            'form.emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'form.emergency_contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'form.bank_name' => ['nullable', 'string', 'max:120'],
            'form.bank_account_number' => ['nullable', 'string', 'max:60'],
            'form.bank_account_name' => ['nullable', 'string', 'max:255'],
            'form.notes' => ['nullable', 'string'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.full_name' => 'full name',
            'form.code' => 'employee code',
            'form.branch_id' => 'branch',
            'form.department_id' => 'department',
            'form.position_id' => 'position',
            'form.manager_id' => 'manager',
            'form.employment_type' => 'employment type',
            'form.status' => 'status',
            'form.start_date' => 'start date',
            'form.nik' => 'NIK',
            'form.npwp' => 'NPWP',
        ];
    }

    public function render()
    {
        return view('livewire.hr.employees.edit', [
            'branches' => $this->branches,
            'departments' => $this->departments,
            'positions' => $this->positions,
            'managers' => $this->managers,
            'employmentTypes' => $this->employmentTypes,
            'employmentClasses' => $this->employmentClasses,
            'workModes' => $this->workModes,
            'statuses' => $this->statuses,
        ])->layoutData([
            'pageTitle' => 'Human Resource',
            'showBrand' => false,
            'navLinks' => HumanResourceNavigation::links('people', 'employees'),
        ]);
    }
}
