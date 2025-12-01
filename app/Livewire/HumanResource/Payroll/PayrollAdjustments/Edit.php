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
class Edit extends Component
{
    public PayrollAdjustment $payrollAdjustment;
    public array $form = [];
    public array $employees = [];
    public array $types = ['allowance', 'deduction', 'bonus', 'overtime', 'reimbursement', 'other'];
    public array $statuses = ['pending', 'approved', 'rejected', 'applied'];

    public function mount(PayrollAdjustment $payrollAdjustment): void
    {
        $this->payrollAdjustment = $payrollAdjustment;
        $this->form = [
            'code' => $payrollAdjustment->code,
            'employee_id' => $payrollAdjustment->employee_id,
            'type' => $payrollAdjustment->type,
            'name' => $payrollAdjustment->name,
            'description' => $payrollAdjustment->description,
            'amount' => $payrollAdjustment->amount,
            'is_recurring' => $payrollAdjustment->is_recurring,
            'effective_date' => $payrollAdjustment->effective_date?->format('Y-m-d'),
            'end_date' => $payrollAdjustment->end_date?->format('Y-m-d'),
            'status' => $payrollAdjustment->status,
        ];
        $this->employees = Employee::orderBy('full_name')->get(['id', 'full_name', 'code'])->toArray();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];
        $this->payrollAdjustment->update($validated);
        session()->flash('status', 'Adjustment updated successfully');
        $this->redirect(route('hr.payroll-adjustments'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('payroll_adjustments', 'code')->ignore($this->payrollAdjustment->id)],
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
        return view('livewire.hr.payroll.payroll-adjustments.edit', [
            'employees' => $this->employees,
            'types' => $this->types,
            'statuses' => $this->statuses,
            'payrollAdjustment' => $this->payrollAdjustment,
        ])->layoutData([
            'pageTitle' => 'Edit Adjustment',
            'pageTagline' => 'HR · Payroll',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('payroll', 'payroll-adjustments'),
        ]);
    }
}
