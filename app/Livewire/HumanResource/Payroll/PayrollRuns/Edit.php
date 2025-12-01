<?php

namespace App\Livewire\HumanResource\Payroll\PayrollRuns;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\PayrollGroup;
use App\Models\PayrollPayout;
use App\Models\PayrollRun;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    public PayrollRun $payrollRun;
    public array $form = [];
    public array $payrollGroups = [];
    public array $branches = [];
    public array $statuses = ['draft', 'processing', 'approved', 'paid', 'cancelled'];

    public string $employeeSearch = '';

    public function mount(PayrollRun $payrollRun): void
    {
        $this->payrollRun = $payrollRun->load('payouts.employee');
        $this->form = [
            'code' => $payrollRun->code,
            'name' => $payrollRun->name,
            'payroll_group_id' => $payrollRun->payroll_group_id,
            'branch_id' => $payrollRun->branch_id,
            'period_start' => $payrollRun->period_start?->format('Y-m-d'),
            'period_end' => $payrollRun->period_end?->format('Y-m-d'),
            'pay_date' => $payrollRun->pay_date?->format('Y-m-d'),
            'status' => $payrollRun->status,
            'notes' => $payrollRun->notes,
        ];
        $this->payrollGroups = PayrollGroup::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code'])->toArray();
        $this->branches = Branch::orderBy('name')->get(['id', 'name'])->toArray();
    }

    public function generatePayouts(): void
    {
        if ($this->payrollRun->status !== 'draft') {
            session()->flash('flash', [
                'type' => 'warning',
                'message' => 'Can only generate payouts for draft runs.',
            ]);
            return;
        }

        // Get employees from the payroll group
        $employees = Employee::where('payroll_group_id', $this->payrollRun->payroll_group_id)
            ->with('employeePayrollItems.payrollItem')
            ->get();

        if ($employees->isEmpty()) {
            session()->flash('flash', [
                'type' => 'warning',
                'message' => 'No employees found in this payroll group.',
            ]);
            return;
        }

        $totalAmount = 0;

        foreach ($employees as $employee) {
            // Calculate earnings and deductions
            $grossAmount = $employee->base_salary ?? 0;
            $totalDeductions = 0;

            foreach ($employee->employeePayrollItems as $item) {
                if ($item->payrollItem->type === 'earning') {
                    $grossAmount += $item->amount;
                } else {
                    $totalDeductions += $item->amount;
                }
            }

            $netAmount = $grossAmount - $totalDeductions;

            // Create or update payout
            PayrollPayout::updateOrCreate(
                [
                    'payroll_run_id' => $this->payrollRun->id,
                    'employee_id' => $employee->id,
                ],
                [
                    'code' => 'PP-' . $this->payrollRun->code . '-' . $employee->id,
                    'gross_salary' => $grossAmount,
                    'total_allowances' => $grossAmount - ($employee->base_salary ?? 0),
                    'total_deductions' => $totalDeductions,
                    'net_salary' => $netAmount,
                    'bank_name' => $employee->bank_name,
                    'bank_account_number' => $employee->bank_account_number,
                    'bank_account_name' => $employee->bank_account_name,
                    'status' => 'pending',
                ]
            );

            $totalAmount += $netAmount;
        }

        // Update payroll run totals
        $this->payrollRun->update([
            'total_net' => $totalAmount,
            'employee_count' => $employees->count(),
        ]);

        $this->payrollRun->refresh();
        $this->payrollRun->load('payouts.employee');

        session()->flash('flash', [
            'type' => 'success',
            'message' => "Generated payouts for {$employees->count()} employees.",
        ]);
    }

    public function removePayout(int $payoutId): void
    {
        PayrollPayout::destroy($payoutId);
        $this->payrollRun->refresh();
        $this->payrollRun->load('payouts.employee');

        session()->flash('flash', [
            'type' => 'success',
            'message' => 'Payout removed.',
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];
        $this->payrollRun->update($validated);
        session()->flash('status', 'Payroll run updated successfully');
        $this->redirect(route('hr.payroll-runs'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('payroll_runs', 'code')->ignore($this->payrollRun->id)],
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
        $payouts = $this->payrollRun->payouts()
            ->with('employee')
            ->when($this->employeeSearch, fn ($q) => $q->whereHas('employee', fn ($e) => $e->where('full_name', 'like', "%{$this->employeeSearch}%")))
            ->orderBy('id')
            ->get();

        $totalGross = $payouts->sum('gross_salary');
        $totalDeductions = $payouts->sum('total_deductions');
        $totalNet = $payouts->sum('net_salary');

        // Get employees from payroll group (for reference)
        $groupEmployees = Employee::where('payroll_group_id', $this->payrollRun->payroll_group_id)
            ->with(['position', 'department'])
            ->when($this->employeeSearch, fn ($q) => $q->where('full_name', 'like', "%{$this->employeeSearch}%"))
            ->orderBy('full_name')
            ->get();

        // Employees with payouts
        $employeesWithPayouts = $payouts->pluck('employee_id')->toArray();

        return view('livewire.hr.payroll.payroll-runs.edit', [
            'payrollGroups' => $this->payrollGroups,
            'branches' => $this->branches,
            'statuses' => $this->statuses,
            'payrollRun' => $this->payrollRun,
            'payouts' => $payouts,
            'totalGross' => $totalGross,
            'totalDeductions' => $totalDeductions,
            'totalNet' => $totalNet,
            'employeeCount' => $payouts->count(),
            'processedCount' => $payouts->where('status', 'paid')->count(),
            'groupEmployees' => $groupEmployees,
            'groupEmployeeCount' => $groupEmployees->count(),
            'employeesWithPayouts' => $employeesWithPayouts,
        ])->layoutData([
            'pageTitle' => 'Edit Payroll Run',
            'pageTagline' => 'HR · Payroll',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('payroll', 'payroll-runs'),
        ]);
    }
}
