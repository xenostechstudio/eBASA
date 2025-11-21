<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Branch>
 */
class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition(): array
    {
        $city = $this->faker->city();

        return [
            'code' => Str::upper(Str::random(4)),
            'name' => $city.' Branch',
            'city' => $city,
            'province' => $this->faker->state(),
            'address' => $this->faker->streetAddress(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'manager_name' => $this->faker->name(),
            'is_active' => $this->faker->boolean(90),
            'meta' => null,
        ];
    }
}
