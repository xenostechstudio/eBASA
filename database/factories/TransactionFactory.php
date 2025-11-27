<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 10000, 500000);
        $discountAmount = $this->faker->boolean(30) ? $this->faker->randomFloat(2, 1000, $subtotal * 0.2) : 0;
        $taxAmount = ($subtotal - $discountAmount) * 0.11;
        $totalAmount = $subtotal - $discountAmount + $taxAmount;
        $paidAmount = $this->faker->randomFloat(2, $totalAmount, $totalAmount + 50000);
        $changeAmount = $paidAmount - $totalAmount;

        $status = $this->faker->randomElement(['completed', 'completed', 'completed', 'pending', 'cancelled', 'refunded']);
        $completedAt = $status === 'completed' ? $this->faker->dateTimeBetween('-30 days', 'now') : null;

        return [
            'transaction_code' => 'TRX-' . strtoupper($this->faker->unique()->bothify('??####')),
            'branch_id' => Branch::inRandomOrder()->first()?->id ?? Branch::factory(),
            'cashier_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'type' => $this->faker->randomElement(['sale', 'sale', 'sale', 'refund']),
            'status' => $status,
            'payment_method' => $this->faker->randomElement(['cash', 'cash', 'card', 'qris', 'transfer']),
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'tax_amount' => round($taxAmount, 2),
            'total_amount' => round($totalAmount, 2),
            'paid_amount' => round($paidAmount, 2),
            'change_amount' => round($changeAmount, 2),
            'notes' => $this->faker->boolean(20) ? $this->faker->sentence() : null,
            'customer_name' => $this->faker->boolean(40) ? $this->faker->name() : null,
            'customer_phone' => $this->faker->boolean(30) ? $this->faker->phoneNumber() : null,
            'completed_at' => $completedAt,
            'created_at' => $completedAt ?? $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'completed_at' => null,
        ]);
    }

    public function refund(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'refund',
            'status' => 'refunded',
        ]);
    }
}
