<?php

namespace App\Livewire\HumanResource\Payroll\PayrollAdjustments;

use App\Models\Employee;
use App\Models\PayrollAdjustment;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Create extends Component
{
    public array $form = [
        'code' => '',
        'employee_id' => null,
        'type' => 'allowance',
        'name' => '',
        'description' => '',
        'amount' => 0,
        'is_recurring' => false,
        'effective_date' => null,
        'end_date' => null,
        'status' => 'pending',
    ];

    public array $employees = [];
    public array $types = ['allowance', 'deduction', 'bonus', 'overtime', 'reimbursement', 'other'];
    public array $statuses = ['pending', 'approved', 'rejected', 'applied'];

    public function mount(): void
    {
        $this->employees = Employee::orderBy('full_name')->get(['id', 'full_name', 'code'])->toArray();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];
        PayrollAdjustment::create($validated);
        session()->flash('status', 'Adjustment created successfully');
        $this->redirect(route('hr.payroll-adjustments'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('payroll_adjustments', 'code')],
            'form.employee_id' => ['required', 'integer', 'exists:employees,id'],
            'form.type' => ['required', Rule::in($this->types)],
            'form.name' => ['required', 'string', 'max:255'],
            'form.description' => ['nullable', 'string'],
            'form.amount' => ['required', 'numeric', 'min:0'],
            'form.is_recurring' => ['boolean'],
            'form.effective_date' => ['nullable', 'date'],
            'form.end_date' => ['nullable', 'date', 'after_or_equal:form.effective_date'],
            'form.status' => ['required', Rule::in($this->statuses)],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.code' => 'code',
            'form.employee_id' => 'employee',
            'form.type' => 'type',
            'form.name' => 'name',
            'form.amount' => 'amount',
        ];
    }

    public function render(): View
    {
        return view('livewire.hr.payroll.payroll-adjustments.create', [
            'employees' => $this->employees,
            'types' => $this->types,
            'statuses' => $this->statuses,
        ])->layoutData([
            'pageTitle' => 'New Adjustment',
            'pageTagline' => 'HR · Payroll',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('payroll', 'payroll-adjustments'),
        ]);
    }
}
