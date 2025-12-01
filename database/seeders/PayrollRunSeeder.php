<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\PayrollGroup;
use App\Models\PayrollPayout;
use App\Models\PayrollRun;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PayrollRunSeeder extends Seeder
{
    public function run(): void
    {
        $payrollGroups = PayrollGroup::with('branch')->where('is_active', true)->get();

        if ($payrollGroups->isEmpty()) {
            return;
        }

        // Generate payroll runs for the last 3 months
        $periods = [
            ['start' => Carbon::now()->subMonths(2)->startOfMonth(), 'end' => Carbon::now()->subMonths(2)->endOfMonth()],
            ['start' => Carbon::now()->subMonth()->startOfMonth(), 'end' => Carbon::now()->subMonth()->endOfMonth()],
            ['start' => Carbon::now()->startOfMonth(), 'end' => Carbon::now()->endOfMonth()],
        ];

        $statuses = ['paid', 'paid', 'draft']; // Past months are paid, current month is draft

        foreach ($payrollGroups as $group) {
            foreach ($periods as $index => $period) {
                $periodStart = $period['start'];
                $periodEnd = $period['end'];
                $status = $statuses[$index];

                // Check if run already exists for this group and period
                $exists = PayrollRun::where('payroll_group_id', $group->id)
                    ->where('period_start', $periodStart->format('Y-m-d'))
                    ->where('period_end', $periodEnd->format('Y-m-d'))
                    ->exists();

                if ($exists) {
                    continue;
                }

                $monthName = $periodStart->format('F Y');
                $code = 'PR-' . $group->code . '-' . $periodStart->format('Ym');

                // Get employees in this payroll group
                $employees = Employee::where('payroll_group_id', $group->id)
                    ->where('status', 'active')
                    ->with('payrollItems')
                    ->get();

                $totalGross = 0;
                $totalDeductions = 0;
                $totalNet = 0;

                // Create payroll run
                $payrollRun = PayrollRun::create([
                    'code' => $code,
                    'name' => "Gaji {$monthName} - {$group->name}",
                    'payroll_group_id' => $group->id,
                    'branch_id' => $group->branch_id,
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd,
                    'pay_date' => $periodEnd->copy()->addDays($group->pay_day ?? 5),
                    'status' => $status,
                    'notes' => "Payroll run for {$group->name} - {$monthName}",
                ]);

                // Create payouts for each employee
                foreach ($employees as $employee) {
                    $baseSalary = $employee->base_salary ?? 0;

                    // Calculate earnings from payroll items
                    $earnings = $employee->payrollItems
                        ->where('type', 'earning')
                        ->sum('pivot.amount');

                    // Calculate deductions from payroll items
                    $deductions = $employee->payrollItems
                        ->where('type', 'deduction')
                        ->sum('pivot.amount');

                    $grossAmount = $baseSalary + $earnings;
                    $netAmount = $grossAmount - $deductions;

                    PayrollPayout::create([
                        'code' => 'PP-' . $payrollRun->code . '-' . $employee->id,
                        'payroll_run_id' => $payrollRun->id,
                        'employee_id' => $employee->id,
                        'gross_salary' => $grossAmount,
                        'total_allowances' => $earnings,
                        'total_deductions' => $deductions,
                        'net_salary' => $netAmount,
                        'bank_name' => $employee->bank_name,
                        'bank_account_number' => $employee->bank_account_number,
                        'bank_account_name' => $employee->bank_account_name,
                        'status' => $status === 'paid' ? 'paid' : 'pending',
                        'paid_at' => $status === 'paid' ? $payrollRun->pay_date : null,
                    ]);

                    $totalGross += $grossAmount;
                    $totalDeductions += $deductions;
                    $totalNet += $netAmount;
                }

                // Update payroll run totals
                $payrollRun->update([
                    'total_gross' => $totalGross,
                    'total_deductions' => $totalDeductions,
                    'total_net' => $totalNet,
                    'employee_count' => $employees->count(),
                    'approved_at' => $status === 'paid' ? $periodEnd->copy()->addDays(3) : null,
                ]);
            }
        }
    }
}
