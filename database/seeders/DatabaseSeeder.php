<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            RolePermissionSeeder::class,
            BranchSeeder::class,
            DepartmentSeeder::class,
            PositionSeeder::class,
            EmployeeSeeder::class,
            PayrollItemSeeder::class,
            PayrollGroupSeeder::class,
            EmployeePayrollItemSeeder::class,
            PayrollRunSeeder::class,
            ShiftSeeder::class,
            LeaveTypeSeeder::class,
            ProductSeeder::class,
            SupplierSeeder::class,
            BranchProductSeeder::class,
            BundleSeeder::class,
            PriceListSeeder::class,
            UserSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
