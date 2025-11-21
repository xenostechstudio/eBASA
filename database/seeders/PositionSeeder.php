<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();

        if ($departments->isEmpty()) {
            $departments = Department::factory()->count(5)->create();
        }

        $departments->each(function (Department $department) {
            Position::factory()
                ->count(3)
                ->state([
                    'department_id' => $department->id,
                    'branch_id' => $department->branch_id,
                ])
                ->create();
        });
    }
}
