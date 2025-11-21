<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $positions = Position::with('department')->get();

        if ($positions->isEmpty()) {
            $positions = Position::factory()->count(5)->create();
        }

        $positions->each(function (Position $position) {
            Employee::factory()
                ->count(4)
                ->state([
                    'position_id' => $position->id,
                    'department_id' => $position->department_id,
                    'branch_id' => $position->branch_id ?? optional($position->department)->branch_id,
                ])
                ->create();
        });
    }
}
