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

        $branches->each(function (Branch $branch) {
            Department::factory()
                ->count(2)
                ->state(['branch_id' => $branch->id])
                ->create();
        });
    }
}
