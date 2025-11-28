<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\CashierShift;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $users = User::all();
        $products = Product::all();

        if ($branches->isEmpty() || $users->isEmpty()) {
            return;
        }

        // Create cashier shifts
        foreach ($branches as $branch) {
            for ($i = 0; $i < 3; $i++) {
                CashierShift::create([
                    'shift_code' => 'SFT-' . strtoupper(fake()->unique()->bothify('??####')),
                    'branch_id' => $branch->id,
                    'cashier_id' => $users->random()->id,
                    'status' => fake()->randomElement(['open', 'closed', 'closed']),
                    'opening_cash' => fake()->randomFloat(2, 100000, 500000),
                    'closing_cash' => fake()->boolean(70) ? fake()->randomFloat(2, 500000, 2000000) : null,
                    'expected_cash' => fake()->boolean(70) ? fake()->randomFloat(2, 500000, 2000000) : null,
                    'cash_difference' => fake()->boolean(70) ? fake()->randomFloat(2, -50000, 50000) : null,
                    'total_transactions' => fake()->numberBetween(10, 100),
                    'total_sales' => fake()->randomFloat(2, 500000, 5000000),
                    'total_refunds' => fake()->randomFloat(2, 0, 100000),
                    'opened_at' => fake()->dateTimeBetween('-30 days', 'now'),
                    'closed_at' => fake()->boolean(70) ? fake()->dateTimeBetween('-30 days', 'now') : null,
                ]);
            }
        }

        $shifts = CashierShift::all();

        // Create transactions with items
        for ($i = 0; $i < 100; $i++) {
            $branch = $branches->random();
            $status = fake()->randomElement(['completed', 'completed', 'completed', 'pending', 'cancelled', 'refunded']);
            $completedAt = $status === 'completed' ? fake()->dateTimeBetween('-30 days', 'now') : null;

            $transaction = Transaction::create([
                'transaction_code' => 'TRX-' . strtoupper(fake()->unique()->bothify('??####')),
                'branch_id' => $branch->id,
                'cashier_id' => $users->random()->id,
                'shift_id' => $shifts->random()->id,
                'type' => fake()->randomElement(['sale', 'sale', 'sale', 'refund']),
                'status' => $status,
                'payment_method' => fake()->randomElement(['cash', 'cash', 'card', 'qris', 'transfer']),
                'subtotal' => 0,
                'discount_amount' => fake()->boolean(30) ? fake()->randomFloat(2, 1000, 10000) : 0,
                'tax_amount' => 0,
                'total_amount' => 0,
                'paid_amount' => 0,
                'change_amount' => 0,
                'notes' => fake()->boolean(20) ? fake()->sentence() : null,
                'customer_name' => fake()->boolean(40) ? fake()->name() : null,
                'customer_phone' => fake()->boolean(30) ? fake()->phoneNumber() : null,
                'completed_at' => $completedAt,
                'created_at' => $completedAt ?? fake()->dateTimeBetween('-30 days', 'now'),
            ]);

            // Create 1-5 items per transaction
            $itemCount = rand(1, 5);
            $itemsSubtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->isNotEmpty() ? $products->random() : null;
                $quantity = rand(1, 3);
                $unitPrice = $product ? $product->selling_price : fake()->randomFloat(2, 10000, 50000);
                $discountAmount = fake()->boolean(20) ? fake()->randomFloat(2, 1000, 5000) : 0;
                $subtotal = ($unitPrice * $quantity) - $discountAmount;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product?->id,
                    'product_name' => $product?->name ?? 'Custom Item',
                    'product_sku' => $product?->sku,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'discount_amount' => $discountAmount,
                    'subtotal' => max(0, $subtotal),
                ]);

                $itemsSubtotal += max(0, $subtotal);
            }

            // Update transaction totals
            $discountAmount = $transaction->discount_amount;
            $taxAmount = ($itemsSubtotal - $discountAmount) * 0.11;
            $totalAmount = $itemsSubtotal - $discountAmount + $taxAmount;
            $paidAmount = $totalAmount + (rand(0, 50) * 1000);

            $transaction->update([
                'subtotal' => $itemsSubtotal,
                'tax_amount' => round($taxAmount, 2),
                'total_amount' => round($totalAmount, 2),
                'paid_amount' => round($paidAmount, 2),
                'change_amount' => round($paidAmount - $totalAmount, 2),
            ]);
        }
    }
}
