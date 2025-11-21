<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'code' => Str::upper(Str::random(4)),
            'name' => $this->faker->words(2, true).' Department',
            'branch_id' => Branch::factory(),
            'parent_id' => null,
            'lead_name' => $this->faker->name(),
            'lead_email' => $this->faker->companyEmail(),
            'description' => $this->faker->sentence(),
            'meta' => null,
        ];
    }
}
