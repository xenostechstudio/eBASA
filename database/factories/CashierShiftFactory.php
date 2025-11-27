<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\CashierShift;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashierShiftFactory extends Factory
{
    protected $model = CashierShift::class;

    public function definition(): array
    {
        $openedAt = $this->faker->dateTimeBetween('-30 days', 'now');
        $isClosed = $this->faker->boolean(80);
        $closedAt = $isClosed ? $this->faker->dateTimeBetween($openedAt, 'now') : null;

        $openingCash = $this->faker->randomFloat(2, 100000, 500000);
        $totalSales = $this->faker->randomFloat(2, 500000, 5000000);
        $totalRefunds = $this->faker->randomFloat(2, 0, $totalSales * 0.1);
        $expectedCash = $openingCash + $totalSales - $totalRefunds;
        $closingCash = $isClosed ? $expectedCash + $this->faker->randomFloat(2, -50000, 50000) : null;
        $cashDifference = $isClosed ? $closingCash - $expectedCash : null;

        return [
            'shift_code' => 'SFT-' . strtoupper($this->faker->unique()->bothify('??####')),
            'branch_id' => Branch::inRandomOrder()->first()?->id ?? Branch::factory(),
            'cashier_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'status' => $isClosed ? 'closed' : 'open',
            'opening_cash' => $openingCash,
            'closing_cash' => $closingCash,
            'expected_cash' => $isClosed ? round($expectedCash, 2) : null,
            'cash_difference' => $isClosed ? round($cashDifference, 2) : null,
            'total_transactions' => $this->faker->numberBetween(10, 100),
            'total_sales' => round($totalSales, 2),
            'total_refunds' => round($totalRefunds, 2),
            'opening_notes' => $this->faker->boolean(30) ? $this->faker->sentence() : null,
            'closing_notes' => $isClosed && $this->faker->boolean(30) ? $this->faker->sentence() : null,
            'opened_at' => $openedAt,
            'closed_at' => $closedAt,
        ];
    }

    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
            'closing_cash' => null,
            'expected_cash' => null,
            'cash_difference' => null,
            'closed_at' => null,
            'closing_notes' => null,
        ]);
    }

    public function closed(): static
    {
        return $this->state(function (array $attributes) {
            $expectedCash = $attributes['opening_cash'] + $attributes['total_sales'] - $attributes['total_refunds'];
            $closingCash = $expectedCash + fake()->randomFloat(2, -10000, 10000);

            return [
                'status' => 'closed',
                'closing_cash' => round($closingCash, 2),
                'expected_cash' => round($expectedCash, 2),
                'cash_difference' => round($closingCash - $expectedCash, 2),
                'closed_at' => now(),
            ];
        });
    }
}
