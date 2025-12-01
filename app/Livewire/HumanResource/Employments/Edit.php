<?php

namespace App\Livewire\HumanResource\Employments;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeePayrollItem;
use App\Models\PayrollGroup;
use App\Models\PayrollItem;
use App\Models\Position;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    public Employee $employee;

    /**
     * @var array<string, mixed>
     */
    public array $form = [];

    public array $employees = [];
    public array $branches = [];
    public array $departments = [];
    public array $positions = [];
    public array $payrollGroups = [];
    public array $payrollItems = [];
    public array $employeePayrollItems = [];

    public array $employmentTypes = ['full_time', 'part_time', 'contract'];
    public array $employmentClasses = ['permanent', 'probation', 'seasonal'];
    public array $workModes = ['onsite', 'hybrid', 'remote'];
    public array $statuses = ['active', 'on_leave', 'probation'];

    // For adding new payroll item
    public ?int $newPayrollItemId = null;
    public float $newPayrollItemAmount = 0;

    public function mount(Employee $employee): void
    {
        $this->employee = $employee->load('employeePayrollItems.payrollItem');

        $this->form = [
            'employee_id' => $employee->id,
            'branch_id' => $employee->branch_id,
            'department_id' => $employee->department_id,
            'position_id' => $employee->position_id,
            'employment_type' => $employee->employment_type,
            'employment_class' => $employee->employment_class,
            'work_mode' => $employee->work_mode,
            'status' => $employee->status,
            'salary_band' => $employee->salary_band,
            'base_salary' => $employee->base_salary ?? 0,
            'payroll_group_id' => $employee->payroll_group_id,
            'start_date' => $employee->start_date?->format('Y-m-d'),
            'probation_end_date' => $employee->probation_end_date?->format('Y-m-d'),
            'notes' => $employee->notes,
        ];

        $this->loadFormData();
        $this->loadEmployeePayrollItems();
    }

    public function loadFormData(): void
    {
        $this->employees = Employee::orderBy('full_name')->get(['id', 'full_name', 'code'])->toArray();
        $this->branches = Branch::orderBy('name')->get(['id', 'name'])->toArray();
        $this->departments = Department::orderBy('name')->get(['id', 'name'])->toArray();
        $this->positions = Position::orderBy('title')->get(['id', 'title'])->toArray();
        $this->payrollGroups = PayrollGroup::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code'])->toArray();
        $this->payrollItems = PayrollItem::where('is_active', true)->orderBy('sort_order')->get(['id', 'name', 'code', 'type', 'default_amount'])->toArray();
    }

    public function loadEmployeePayrollItems(): void
    {
        $this->employeePayrollItems = $this->employee->employeePayrollItems()
            ->with('payrollItem')
            ->orderBy('id')
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'payroll_item_id' => $item->payroll_item_id,
                'name' => $item->payrollItem->name ?? '',
                'code' => $item->payrollItem->code ?? '',
                'type' => $item->payrollItem->type ?? 'earning',
                'amount' => $item->amount,
                'is_active' => $item->is_active,
            ])
            ->toArray();
    }

    public function addPayrollItem(): void
    {
        if (!$this->newPayrollItemId) {
            return;
        }

        // Check if already exists
        $exists = EmployeePayrollItem::where('employee_id', $this->employee->id)
            ->where('payroll_item_id', $this->newPayrollItemId)
            ->exists();

        if ($exists) {
            session()->flash('flash', [
                'type' => 'warning',
                'message' => 'This payroll item is already assigned to this employee.',
            ]);
            return;
        }

        $payrollItem = PayrollItem::find($this->newPayrollItemId);
        $amount = $this->newPayrollItemAmount > 0 ? $this->newPayrollItemAmount : ($payrollItem->default_amount ?? 0);

        EmployeePayrollItem::create([
            'employee_id' => $this->employee->id,
            'payroll_item_id' => $this->newPayrollItemId,
            'amount' => $amount,
            'effective_date' => now(),
            'is_active' => true,
        ]);

        $this->newPayrollItemId = null;
        $this->newPayrollItemAmount = 0;
        $this->loadEmployeePayrollItems();

        session()->flash('flash', [
            'type' => 'success',
            'message' => 'Payroll item added successfully.',
        ]);
    }

    public function updatePayrollItemAmount(int $itemId, float $amount): void
    {
        EmployeePayrollItem::where('id', $itemId)->update(['amount' => $amount]);
        $this->loadEmployeePayrollItems();
    }

    public function removePayrollItem(int $itemId): void
    {
        EmployeePayrollItem::destroy($itemId);
        $this->loadEmployeePayrollItems();

        session()->flash('flash', [
            'type' => 'success',
            'message' => 'Payroll item removed.',
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];

        $this->employee->fill([
            'branch_id' => $validated['branch_id'],
            'department_id' => $validated['department_id'],
            'position_id' => $validated['position_id'],
            'employment_type' => $validated['employment_type'],
            'employment_class' => $validated['employment_class'],
            'work_mode' => $validated['work_mode'],
            'status' => $validated['status'],
            'salary_band' => $validated['salary_band'],
            'base_salary' => $validated['base_salary'],
            'payroll_group_id' => $validated['payroll_group_id'],
            'start_date' => $validated['start_date'],
            'probation_end_date' => $validated['probation_end_date'],
            'notes' => $validated['notes'],
        ])->save();

        session()->flash('status', 'Employment record updated');

        $this->dispatch('notify', message: 'Employment saved');

        $this->redirect(route('hr.employments'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.employee_id' => ['required', 'integer', Rule::exists('employees', 'id')],
            'form.branch_id' => ['required', 'integer', Rule::exists('branches', 'id')],
            'form.department_id' => ['nullable', 'integer', Rule::exists('departments', 'id')],
            'form.position_id' => ['nullable', 'integer', Rule::exists('positions', 'id')],
            'form.employment_type' => ['required', Rule::in($this->employmentTypes)],
            'form.employment_class' => ['nullable', Rule::in($this->employmentClasses)],
            'form.work_mode' => ['nullable', Rule::in($this->workModes)],
            'form.status' => ['required', Rule::in($this->statuses)],
            'form.salary_band' => ['nullable', 'string', 'max:50'],
            'form.base_salary' => ['required', 'numeric', 'min:0'],
            'form.payroll_group_id' => ['nullable', 'integer', Rule::exists('payroll_groups', 'id')],
            'form.start_date' => ['required', 'date'],
            'form.probation_end_date' => ['nullable', 'date', 'after_or_equal:form.start_date'],
            'form.notes' => ['nullable', 'string'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.employee_id' => 'employee',
            'form.branch_id' => 'branch',
            'form.department_id' => 'department',
            'form.position_id' => 'position',
            'form.employment_type' => 'employment type',
            'form.status' => 'status',
            'form.base_salary' => 'base salary',
            'form.payroll_group_id' => 'payroll group',
            'form.start_date' => 'start date',
        ];
    }

    public function render(): View
    {
        return view('livewire.hr.employments.edit', [
            'employees' => $this->employees,
            'branches' => $this->branches,
            'departments' => $this->departments,
            'positions' => $this->positions,
            'payrollGroups' => $this->payrollGroups,
            'payrollItems' => $this->payrollItems,
            'employeePayrollItems' => $this->employeePayrollItems,
            'employmentTypes' => $this->employmentTypes,
            'employmentClasses' => $this->employmentClasses,
            'workModes' => $this->workModes,
            'statuses' => $this->statuses,
            'employee' => $this->employee,
        ])->layoutData([
            'pageTitle' => 'Edit Employment',
            'pageTagline' => 'HR · People',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('people', 'employments'),
        ]);
    }
}
