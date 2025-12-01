<?php

namespace App\Livewire\HumanResource\Payroll\PayrollItems;

use App\Models\PayrollItem;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    public PayrollItem $payrollItem;
    public array $form = [];

    public function mount(PayrollItem $payrollItem): void
    {
        $this->payrollItem = $payrollItem;
        $this->form = [
            'code' => $payrollItem->code,
            'name' => $payrollItem->name,
            'description' => $payrollItem->description,
            'type' => $payrollItem->type,
            'category' => $payrollItem->category,
            'calculation_type' => $payrollItem->calculation_type,
            'default_amount' => $payrollItem->default_amount,
            'percentage_base' => $payrollItem->percentage_base,
            'is_taxable' => $payrollItem->is_taxable,
            'is_recurring' => $payrollItem->is_recurring,
            'is_active' => $payrollItem->is_active,
            'sort_order' => $payrollItem->sort_order,
        ];
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];

        $this->payrollItem->update($validated);

        session()->flash('flash', [
            'type' => 'success',
            'message' => 'Payroll item updated successfully.',
        ]);

        $this->redirect(route('hr.payroll-items'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('payroll_items', 'code')->ignore($this->payrollItem->id)],
            'form.name' => ['required', 'string', 'max:255'],
            'form.description' => ['nullable', 'string'],
            'form.type' => ['required', Rule::in(PayrollItem::TYPES)],
            'form.category' => ['required', Rule::in(array_keys(PayrollItem::CATEGORIES))],
            'form.calculation_type' => ['required', Rule::in(PayrollItem::CALCULATION_TYPES)],
            'form.default_amount' => ['required', 'numeric', 'min:0'],
            'form.percentage_base' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'form.is_taxable' => ['boolean'],
            'form.is_recurring' => ['boolean'],
            'form.is_active' => ['boolean'],
            'form.sort_order' => ['integer', 'min:0'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.code' => 'code',
            'form.name' => 'name',
            'form.type' => 'type',
            'form.category' => 'category',
            'form.default_amount' => 'default amount',
        ];
    }

    public function render(): View
    {
        return view('livewire.hr.payroll.payroll-items.edit', [
            'types' => PayrollItem::TYPES,
            'categories' => PayrollItem::CATEGORIES,
            'calculationTypes' => PayrollItem::CALCULATION_TYPES,
            'payrollItem' => $this->payrollItem,
        ])->layoutData([
            'pageTitle' => 'Edit Payroll Item',
            'pageTagline' => 'HR · Payroll',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('payroll', 'payroll-items'),
        ]);
    }
}
