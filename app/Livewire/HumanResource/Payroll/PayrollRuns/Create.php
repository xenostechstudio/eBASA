<?php

namespace App\Livewire\HumanResource\Payroll\PayrollRuns;

use App\Models\Branch;
use App\Models\PayrollGroup;
use App\Models\PayrollRun;
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
        'payroll_group_id' => null,
        'branch_id' => null,
        'period_start' => null,
        'period_end' => null,
        'pay_date' => null,
        'status' => 'draft',
        'notes' => '',
    ];

    public array $payrollGroups = [];
    public array $branches = [];
    public array $statuses = ['draft', 'processing', 'approved', 'paid', 'cancelled'];

    public function mount(): void
    {
        $this->payrollGroups = PayrollGroup::where('is_active', true)->orderBy('name')->get(['id', 'name'])->toArray();
        $this->branches = Branch::orderBy('name')->get(['id', 'name'])->toArray();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];
        PayrollRun::create($validated);
        session()->flash('status', 'Payroll run created successfully');
        $this->redirect(route('hr.payroll-runs'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('payroll_runs', 'code')],
            'form.name' => ['required', 'string', 'max:255'],
            'form.payroll_group_id' => ['required', 'integer', 'exists:payroll_groups,id'],
            'form.branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'form.period_start' => ['required', 'date'],
            'form.period_end' => ['required', 'date', 'after_or_equal:form.period_start'],
            'form.pay_date' => ['required', 'date'],
            'form.status' => ['required', Rule::in($this->statuses)],
            'form.notes' => ['nullable', 'string'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.code' => 'code',
            'form.name' => 'name',
            'form.payroll_group_id' => 'payroll group',
            'form.period_start' => 'period start',
            'form.period_end' => 'period end',
            'form.pay_date' => 'pay date',
        ];
    }

    public function render(): View
    {
        return view('livewire.hr.payroll.payroll-runs.create', [
            'payrollGroups' => $this->payrollGroups,
            'branches' => $this->branches,
            'statuses' => $this->statuses,
        ])->layoutData([
            'pageTitle' => 'New Payroll Run',
            'pageTagline' => 'HR · Payroll',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('payroll', 'payroll-runs'),
        ]);
    }
}
