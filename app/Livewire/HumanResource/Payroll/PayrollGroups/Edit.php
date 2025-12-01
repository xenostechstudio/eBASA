<?php

namespace App\Livewire\HumanResource\Payroll\PayrollGroups;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\PayrollGroup;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    use WithPagination;

    public PayrollGroup $payrollGroup;

    public array $form = [];

    public array $branches = [];
    public array $payFrequencies = ['weekly', 'biweekly', 'monthly'];

    public string $employeeSearch = '';
    public array $availableEmployees = [];
    public ?int $selectedEmployeeId = null;

    public function mount(PayrollGroup $payrollGroup): void
    {
        $this->payrollGroup = $payrollGroup->load('employees.position', 'employees.department');

        $this->form = [
            'code' => $payrollGroup->code,
            'name' => $payrollGroup->name,
            'description' => $payrollGroup->description,
            'pay_frequency' => $payrollGroup->pay_frequency,
            'pay_day' => $payrollGroup->pay_day,
            'branch_id' => $payrollGroup->branch_id,
            'is_active' => $payrollGroup->is_active,
        ];

        $this->branches = Branch::orderBy('name')->get(['id', 'name'])->toArray();
        $this->loadAvailableEmployees();
    }

    public function loadAvailableEmployees(): void
    {
        $this->availableEmployees = Employee::whereNull('payroll_group_id')
            ->orWhere('payroll_group_id', '!=', $this->payrollGroup->id)
            ->orderBy('full_name')
            ->get(['id', 'full_name', 'code'])
            ->toArray();
    }

    public function addEmployee(): void
    {
        if (!$this->selectedEmployeeId) {
            return;
        }

        Employee::where('id', $this->selectedEmployeeId)
            ->update(['payroll_group_id' => $this->payrollGroup->id]);

        $this->selectedEmployeeId = null;
        $this->payrollGroup->refresh();
        $this->payrollGroup->load('employees.position', 'employees.department');
        $this->loadAvailableEmployees();

        session()->flash('flash', [
            'type' => 'success',
            'message' => 'Employee added to payroll group.',
        ]);
    }

    public function removeEmployee(int $employeeId): void
    {
        Employee::where('id', $employeeId)
            ->where('payroll_group_id', $this->payrollGroup->id)
            ->update(['payroll_group_id' => null]);

        $this->payrollGroup->refresh();
        $this->payrollGroup->load('employees.position', 'employees.department');
        $this->loadAvailableEmployees();

        session()->flash('flash', [
            'type' => 'success',
            'message' => 'Employee removed from payroll group.',
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];

        $this->payrollGroup->update($validated);

        session()->flash('status', 'Payroll group updated successfully');

        $this->redirect(route('hr.payroll-groups'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('payroll_groups', 'code')->ignore($this->payrollGroup->id)],
            'form.name' => ['required', 'string', 'max:255'],
            'form.description' => ['nullable', 'string'],
            'form.pay_frequency' => ['required', Rule::in($this->payFrequencies)],
            'form.pay_day' => ['required', 'integer', 'min:1', 'max:31'],
            'form.branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'form.is_active' => ['boolean'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.code' => 'code',
            'form.name' => 'name',
            'form.pay_frequency' => 'pay frequency',
            'form.pay_day' => 'pay day',
        ];
    }

    public function render(): View
    {
        $employees = $this->payrollGroup->employees()
            ->with(['position', 'department'])
            ->when($this->employeeSearch, fn ($q) => $q->where('full_name', 'like', "%{$this->employeeSearch}%"))
            ->orderBy('full_name')
            ->get();

        return view('livewire.hr.payroll.payroll-groups.edit', [
            'branches' => $this->branches,
            'payFrequencies' => $this->payFrequencies,
            'payrollGroup' => $this->payrollGroup,
            'employees' => $employees,
            'availableEmployees' => $this->availableEmployees,
        ])->layoutData([
            'pageTitle' => 'Edit Payroll Group',
            'pageTagline' => 'HR · Payroll',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('payroll', 'payroll-groups'),
        ]);
    }
}
