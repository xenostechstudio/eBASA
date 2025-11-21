<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Position>
 */
class PositionFactory extends Factory
{
    protected $model = Position::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $levels = ['P1', 'P2', 'M1', 'M2', 'M3'];

        return [
            'code' => Str::upper('POS-'.Str::random(4)),
            'title' => $this->faker->jobTitle(),
            'level' => $this->faker->randomElement($levels),
            'job_family' => $this->faker->randomElement(['Retail', 'Operations', 'Finance', 'People', 'Supply Chain']),
            'is_people_manager' => $this->faker->boolean(40),
            'department_id' => Department::factory(),
            'branch_id' => Branch::factory(),
            'description' => $this->faker->sentence(),
            'meta' => null,
        ];
    }
}
