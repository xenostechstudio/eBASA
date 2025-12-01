<?php

namespace Database\Factories;

use App\Models\PayrollItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PayrollItem>
 */
class PayrollItemFactory extends Factory
{
    protected $model = PayrollItem::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['earning', 'deduction']);

        $earningCategories = ['basic_salary', 'allowance', 'bonus', 'overtime', 'thr', 'other_earning'];
        $deductionCategories = ['bpjs_kesehatan', 'bpjs_ketenagakerjaan', 'pph21', 'loan', 'other_deduction'];

        $category = $type === 'earning'
            ? $this->faker->randomElement($earningCategories)
            : $this->faker->randomElement($deductionCategories);

        return [
            'code' => Str::upper('PI-' . $this->faker->unique()->numerify('###')),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'type' => $type,
            'category' => $category,
            'calculation_type' => $this->faker->randomElement(['fixed', 'percentage']),
            'default_amount' => $this->faker->randomElement([100000, 250000, 500000, 750000, 1000000]),
            'percentage_base' => $this->faker->boolean(30) ? $this->faker->randomFloat(2, 1, 10) : null,
            'is_taxable' => $type === 'earning',
            'is_recurring' => $this->faker->boolean(80),
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }

    public function earning(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'earning',
            'category' => $this->faker->randomElement(['basic_salary', 'allowance', 'bonus', 'overtime', 'thr', 'other_earning']),
        ]);
    }

    public function deduction(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'deduction',
            'category' => $this->faker->randomElement(['bpjs_kesehatan', 'bpjs_ketenagakerjaan', 'pph21', 'loan', 'other_deduction']),
        ]);
    }
}
