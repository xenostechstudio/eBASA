<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();

        if ($branches->isEmpty()) {
            $branches = Branch::factory()->count(3)->create();
        }

        $departmentDefinitions = [
            [
                'suffix' => 'OPS',
                'name' => 'Operasional Toko',
                'lead_name' => 'Kepala Operasional',
                'lead_email_prefix' => 'ops-head',
            ],
            [
                'suffix' => 'FIN',
                'name' => 'Keuangan & Akuntansi',
                'lead_name' => 'Manager Keuangan',
                'lead_email_prefix' => 'finance-head',
            ],
            [
                'suffix' => 'HRD',
                'name' => 'Sumber Daya Manusia',
                'lead_name' => 'HR Manager',
                'lead_email_prefix' => 'hr-head',
            ],
            [
                'suffix' => 'SCM',
                'name' => 'Supply Chain & Gudang',
                'lead_name' => 'Kepala Gudang',
                'lead_email_prefix' => 'warehouse-head',
            ],
        ];

        foreach ($branches as $branch) {
            foreach ($departmentDefinitions as $definition) {
                $code = $branch->code . '-' . $definition['suffix'];

                Department::updateOrCreate(
                    ['code' => $code],
                    [
                        'name' => $definition['name'] . ' ' . $branch->name,
                        'branch_id' => $branch->id,
                        'parent_id' => null,
                        'lead_name' => $definition['lead_name'] . ' ' . $branch->name,
                        'lead_email' => $definition['lead_email_prefix'] . '.' . strtolower($branch->code) . '@basa.test',
                        'description' => 'Departemen ' . $definition['name'] . ' untuk cabang ' . $branch->name . '.',
                        'meta' => null,
                    ]
                );
            }
        }
    }
}
