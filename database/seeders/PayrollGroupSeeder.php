<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\PayrollGroup;
use Illuminate\Database\Seeder;

class PayrollGroupSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();

        $groups = [
            [
                'code' => 'PG-001',
                'name' => 'Staff Bulanan',
                'description' => 'Grup payroll untuk karyawan staff dengan gaji bulanan',
                'pay_frequency' => 'monthly',
                'pay_day' => 25,
                'is_active' => true,
            ],
            [
                'code' => 'PG-002',
                'name' => 'Manajemen',
                'description' => 'Grup payroll untuk level manajemen',
                'pay_frequency' => 'monthly',
                'pay_day' => 25,
                'is_active' => true,
            ],
        ];

        $createdGroups = [];

        foreach ($groups as $group) {
            // Create one group per branch
            foreach ($branches as $branch) {
                $payrollGroup = PayrollGroup::updateOrCreate(
                    [
                        'code' => $group['code'] . '-' . $branch->id,
                    ],
                    array_merge($group, [
                        'code' => $group['code'] . '-' . $branch->id,
                        'name' => $group['name'] . ' - ' . $branch->name,
                        'branch_id' => $branch->id,
                    ])
                );
                $createdGroups[$branch->id][] = $payrollGroup;
            }
        }

        // Assign employees to payroll groups based on their branch and salary band
        $employees = Employee::whereNull('payroll_group_id')->get();

        foreach ($employees as $employee) {
            $branchId = $employee->branch_id;
            if (!$branchId || !isset($createdGroups[$branchId])) {
                continue;
            }

            $branchGroups = $createdGroups[$branchId];

            // Assign to management group if salary band is M*, otherwise staff group
            $isManagement = str_starts_with($employee->salary_band ?? '', 'M');
            $groupIndex = $isManagement ? 1 : 0;

            if (isset($branchGroups[$groupIndex])) {
                $employee->update(['payroll_group_id' => $branchGroups[$groupIndex]->id]);
            } elseif (isset($branchGroups[0])) {
                $employee->update(['payroll_group_id' => $branchGroups[0]->id]);
            }
        }
    }
}
