<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeePayrollItem;
use App\Models\PayrollGroup;
use App\Models\PayrollItem;
use Illuminate\Database\Seeder;

class EmployeePayrollItemSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $payrollItems = PayrollItem::where('is_active', true)->get();
        $payrollGroups = PayrollGroup::where('is_active', true)->get();

        // Get specific items
        $gajiPokok = $payrollItems->firstWhere('code', 'PI-001');
        $tunjanganTransport = $payrollItems->firstWhere('code', 'PI-002');
        $tunjanganMakan = $payrollItems->firstWhere('code', 'PI-003');
        $tunjanganJabatan = $payrollItems->firstWhere('code', 'PI-004');
        $bpjsKesehatan = $payrollItems->firstWhere('code', 'PI-101');
        $bpjsJht = $payrollItems->firstWhere('code', 'PI-102');
        $bpjsJp = $payrollItems->firstWhere('code', 'PI-103');

        foreach ($employees as $employee) {
            // Assign payroll group based on branch
            if ($payrollGroups->isNotEmpty()) {
                $matchingGroup = $payrollGroups->firstWhere('branch_id', $employee->branch_id);
                if ($matchingGroup) {
                    $employee->update(['payroll_group_id' => $matchingGroup->id]);
                }
            }

            // Assign basic payroll items to each employee
            $itemsToAssign = [
                [
                    'payroll_item_id' => $gajiPokok?->id,
                    'amount' => $employee->base_salary ?? 5000000,
                ],
                [
                    'payroll_item_id' => $tunjanganTransport?->id,
                    'amount' => rand(3, 6) * 100000, // 300k - 600k
                ],
                [
                    'payroll_item_id' => $tunjanganMakan?->id,
                    'amount' => rand(4, 8) * 100000, // 400k - 800k
                ],
                [
                    'payroll_item_id' => $bpjsKesehatan?->id,
                    'amount' => round(($employee->base_salary ?? 5000000) * 0.01), // 1%
                ],
                [
                    'payroll_item_id' => $bpjsJht?->id,
                    'amount' => round(($employee->base_salary ?? 5000000) * 0.02), // 2%
                ],
                [
                    'payroll_item_id' => $bpjsJp?->id,
                    'amount' => round(($employee->base_salary ?? 5000000) * 0.01), // 1%
                ],
            ];

            // Add tunjangan jabatan for managers/supervisors
            if (str_contains(strtolower($employee->position?->name ?? ''), 'manager') ||
                str_contains(strtolower($employee->position?->name ?? ''), 'supervisor') ||
                str_contains(strtolower($employee->position?->name ?? ''), 'kepala')) {
                $itemsToAssign[] = [
                    'payroll_item_id' => $tunjanganJabatan?->id,
                    'amount' => rand(10, 20) * 100000, // 1M - 2M
                ];
            }

            foreach ($itemsToAssign as $item) {
                if ($item['payroll_item_id']) {
                    EmployeePayrollItem::updateOrCreate(
                        [
                            'employee_id' => $employee->id,
                            'payroll_item_id' => $item['payroll_item_id'],
                        ],
                        [
                            'amount' => $item['amount'],
                            'effective_date' => $employee->start_date,
                            'is_active' => true,
                        ]
                    );
                }
            }
        }
    }
}
