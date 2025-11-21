<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'code' => Str::upper('EMP-'.$this->faker->unique()->numerify('###')),
            'full_name' => $this->faker->name(),
            'preferred_name' => $this->faker->boolean(40) ? $this->faker->firstName() : null,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'whatsapp_number' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->date(),
            'nik' => $this->faker->unique()->numerify('################'),
            'npwp' => $this->faker->unique()->numerify('##.###.###.#-###.###'),
            'branch_id' => Branch::factory(),
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
            'manager_id' => null,
            'employment_type' => $this->faker->randomElement(['full_time', 'contract', 'intern']),
            'employment_class' => $this->faker->randomElement(['permanent', 'probation', 'temp']),
            'work_mode' => $this->faker->randomElement(['onsite', 'hybrid', 'remote']),
            'status' => $this->faker->randomElement(['active', 'on_leave', 'probation']),
            'salary_band' => $this->faker->randomElement(['P1', 'P2', 'M1', 'M2', 'M3']),
            'start_date' => $this->faker->dateTimeBetween('-8 years', 'now')->format('Y-m-d'),
            'probation_end_date' => $this->faker->boolean(60) ? $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d') : null,
            'end_date' => null,
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_whatsapp' => $this->faker->phoneNumber(),
            'bank_name' => $this->faker->randomElement(['BCA', 'Mandiri', 'BNI', 'BRI']),
            'bank_account_number' => $this->faker->bankAccountNumber(),
            'bank_account_name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'notes' => $this->faker->boolean(20) ? $this->faker->sentence() : null,
            'meta' => null,
        ];
    }
}
