<?php

namespace App\Livewire\HumanResource\Payroll\PayrollItems;

use App\Models\PayrollItem;
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
        'name' => '',
        'description' => '',
        'type' => 'earning',
        'category' => 'allowance',
        'calculation_type' => 'fixed',
        'default_amount' => 0,
        'percentage_base' => null,
        'is_taxable' => true,
        'is_recurring' => true,
        'is_active' => true,
        'sort_order' => 0,
    ];

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];

        PayrollItem::create($validated);

        session()->flash('flash', [
            'type' => 'success',
            'message' => 'Payroll item created successfully.',
        ]);

        $this->redirect(route('hr.payroll-items'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('payroll_items', 'code')],
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
        return view('livewire.hr.payroll.payroll-items.create', [
            'types' => PayrollItem::TYPES,
            'categories' => PayrollItem::CATEGORIES,
            'calculationTypes' => PayrollItem::CALCULATION_TYPES,
        ])->layoutData([
            'pageTitle' => 'New Payroll Item',
            'pageTagline' => 'HR · Payroll',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('payroll', 'payroll-items'),
        ]);
    }
}
