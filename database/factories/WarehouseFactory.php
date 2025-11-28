<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Warehouse>
 */
class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('WH-???')),
            'name' => $this->faker->company . ' Warehouse',
            'branch_id' => Branch::query()->inRandomOrder()->value('id'),
            'city' => $this->faker->city,
            'province' => $this->faker->state,
            'address' => $this->faker->streetAddress,
            'phone' => $this->faker->phoneNumber,
            'contact_name' => $this->faker->name,
            'is_active' => true,
            'meta' => null,
        ];
    }
}
