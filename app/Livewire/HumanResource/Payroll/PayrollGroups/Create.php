<?php

namespace App\Livewire\HumanResource\Payroll\PayrollGroups;

use App\Models\Branch;
use App\Models\PayrollGroup;
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
        'pay_frequency' => 'monthly',
        'pay_day' => 25,
        'branch_id' => null,
        'is_active' => true,
    ];

    public array $branches = [];
    public array $payFrequencies = ['weekly', 'biweekly', 'monthly'];

    public function mount(): void
    {
        $this->branches = Branch::orderBy('name')->get(['id', 'name'])->toArray();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];

        PayrollGroup::create($validated);

        session()->flash('status', 'Payroll group created successfully');

        $this->redirect(route('hr.payroll-groups'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('payroll_groups', 'code')],
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
        return view('livewire.hr.payroll.payroll-groups.create', [
            'branches' => $this->branches,
            'payFrequencies' => $this->payFrequencies,
        ])->layoutData([
            'pageTitle' => 'New Payroll Group',
            'pageTagline' => 'HR · Payroll',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('payroll', 'payroll-groups'),
        ]);
    }
}
