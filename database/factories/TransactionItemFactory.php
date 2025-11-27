<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionItemFactory extends Factory
{
    protected $model = TransactionItem::class;

    protected array $products = [
        ['name' => 'Nasi Goreng Spesial', 'sku' => 'FD-001', 'price' => 25000],
        ['name' => 'Mie Goreng', 'sku' => 'FD-002', 'price' => 22000],
        ['name' => 'Ayam Bakar', 'sku' => 'FD-003', 'price' => 35000],
        ['name' => 'Es Teh Manis', 'sku' => 'BV-001', 'price' => 5000],
        ['name' => 'Es Jeruk', 'sku' => 'BV-002', 'price' => 8000],
        ['name' => 'Kopi Susu', 'sku' => 'BV-003', 'price' => 15000],
        ['name' => 'Sate Ayam', 'sku' => 'FD-004', 'price' => 30000],
        ['name' => 'Gado-gado', 'sku' => 'FD-005', 'price' => 20000],
        ['name' => 'Soto Ayam', 'sku' => 'FD-006', 'price' => 25000],
        ['name' => 'Bakso', 'sku' => 'FD-007', 'price' => 18000],
        ['name' => 'Air Mineral', 'sku' => 'BV-004', 'price' => 5000],
        ['name' => 'Jus Alpukat', 'sku' => 'BV-005', 'price' => 18000],
    ];

    public function definition(): array
    {
        $product = $this->faker->randomElement($this->products);
        $quantity = $this->faker->numberBetween(1, 5);
        $discountAmount = $this->faker->boolean(20) ? $this->faker->randomFloat(2, 1000, 5000) : 0;
        $subtotal = ($product['price'] * $quantity) - $discountAmount;

        return [
            'transaction_id' => Transaction::factory(),
            'product_name' => $product['name'],
            'product_sku' => $product['sku'],
            'unit_price' => $product['price'],
            'quantity' => $quantity,
            'discount_amount' => $discountAmount,
            'subtotal' => max(0, $subtotal),
            'notes' => $this->faker->boolean(10) ? $this->faker->sentence() : null,
        ];
    }
}
