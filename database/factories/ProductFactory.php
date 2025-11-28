<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    protected array $products = [
        // Food
        ['name' => 'Nasi Goreng Spesial', 'sku' => 'FD-001', 'cost' => 15000, 'price' => 25000, 'category' => 'Food'],
        ['name' => 'Mie Goreng', 'sku' => 'FD-002', 'cost' => 12000, 'price' => 22000, 'category' => 'Food'],
        ['name' => 'Ayam Bakar', 'sku' => 'FD-003', 'cost' => 20000, 'price' => 35000, 'category' => 'Food'],
        ['name' => 'Sate Ayam', 'sku' => 'FD-004', 'cost' => 18000, 'price' => 30000, 'category' => 'Food'],
        ['name' => 'Gado-gado', 'sku' => 'FD-005', 'cost' => 12000, 'price' => 20000, 'category' => 'Food'],
        ['name' => 'Soto Ayam', 'sku' => 'FD-006', 'cost' => 15000, 'price' => 25000, 'category' => 'Food'],
        ['name' => 'Bakso', 'sku' => 'FD-007', 'cost' => 10000, 'price' => 18000, 'category' => 'Food'],
        ['name' => 'Nasi Uduk', 'sku' => 'FD-008', 'cost' => 8000, 'price' => 15000, 'category' => 'Food'],
        ['name' => 'Rendang', 'sku' => 'FD-009', 'cost' => 25000, 'price' => 40000, 'category' => 'Food'],
        ['name' => 'Ikan Bakar', 'sku' => 'FD-010', 'cost' => 22000, 'price' => 38000, 'category' => 'Food'],
        // Beverages
        ['name' => 'Es Teh Manis', 'sku' => 'BV-001', 'cost' => 2000, 'price' => 5000, 'category' => 'Beverages'],
        ['name' => 'Es Jeruk', 'sku' => 'BV-002', 'cost' => 4000, 'price' => 8000, 'category' => 'Beverages'],
        ['name' => 'Kopi Susu', 'sku' => 'BV-003', 'cost' => 8000, 'price' => 15000, 'category' => 'Beverages'],
        ['name' => 'Air Mineral', 'sku' => 'BV-004', 'cost' => 2000, 'price' => 5000, 'category' => 'Beverages'],
        ['name' => 'Jus Alpukat', 'sku' => 'BV-005', 'cost' => 10000, 'price' => 18000, 'category' => 'Beverages'],
        ['name' => 'Es Campur', 'sku' => 'BV-006', 'cost' => 8000, 'price' => 15000, 'category' => 'Beverages'],
        ['name' => 'Teh Tarik', 'sku' => 'BV-007', 'cost' => 6000, 'price' => 12000, 'category' => 'Beverages'],
        // Snacks
        ['name' => 'Kentang Goreng', 'sku' => 'SN-001', 'cost' => 8000, 'price' => 15000, 'category' => 'Snacks'],
        ['name' => 'Pisang Goreng', 'sku' => 'SN-002', 'cost' => 5000, 'price' => 10000, 'category' => 'Snacks'],
        ['name' => 'Tahu Crispy', 'sku' => 'SN-003', 'cost' => 4000, 'price' => 8000, 'category' => 'Snacks'],
        ['name' => 'Tempe Mendoan', 'sku' => 'SN-004', 'cost' => 4000, 'price' => 8000, 'category' => 'Snacks'],
        // Desserts
        ['name' => 'Es Krim', 'sku' => 'DS-001', 'cost' => 5000, 'price' => 12000, 'category' => 'Desserts'],
        ['name' => 'Pudding', 'sku' => 'DS-002', 'cost' => 4000, 'price' => 10000, 'category' => 'Desserts'],
        ['name' => 'Kue Lapis', 'sku' => 'DS-003', 'cost' => 6000, 'price' => 12000, 'category' => 'Desserts'],
    ];

    public function definition(): array
    {
        $product = $this->faker->unique()->randomElement($this->products);

        return [
            'sku' => $product['sku'],
            'name' => $product['name'],
            'barcode' => $this->faker->ean13(),
            'description' => $this->faker->sentence(),
            'category_id' => null, // Will be set in seeder
            'cost_price' => $product['cost'],
            'selling_price' => $product['price'],
            'unit' => 'pcs',
            'stock_quantity' => $this->faker->numberBetween(10, 100),
            'min_stock_level' => 5,
            'is_active' => true,
            'track_inventory' => true,
        ];
    }
}
