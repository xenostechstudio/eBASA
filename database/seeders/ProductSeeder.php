<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class RetailProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $categories = [
            ['name' => 'Food', 'slug' => 'food', 'color' => '#f59e0b', 'sort_order' => 1],
            ['name' => 'Beverages', 'slug' => 'beverages', 'color' => '#3b82f6', 'sort_order' => 2],
            ['name' => 'Snacks', 'slug' => 'snacks', 'color' => '#10b981', 'sort_order' => 3],
            ['name' => 'Desserts', 'slug' => 'desserts', 'color' => '#ec4899', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }

        // Create products
        $products = [
            // Food
            ['name' => 'Nasi Goreng Spesial', 'sku' => 'FD-001', 'cost_price' => 15000, 'selling_price' => 25000, 'category' => 'food'],
            ['name' => 'Mie Goreng', 'sku' => 'FD-002', 'cost_price' => 12000, 'selling_price' => 22000, 'category' => 'food'],
            ['name' => 'Ayam Bakar', 'sku' => 'FD-003', 'cost_price' => 20000, 'selling_price' => 35000, 'category' => 'food'],
            ['name' => 'Sate Ayam', 'sku' => 'FD-004', 'cost_price' => 18000, 'selling_price' => 30000, 'category' => 'food'],
            ['name' => 'Gado-gado', 'sku' => 'FD-005', 'cost_price' => 12000, 'selling_price' => 20000, 'category' => 'food'],
            ['name' => 'Soto Ayam', 'sku' => 'FD-006', 'cost_price' => 15000, 'selling_price' => 25000, 'category' => 'food'],
            ['name' => 'Bakso', 'sku' => 'FD-007', 'cost_price' => 10000, 'selling_price' => 18000, 'category' => 'food'],
            ['name' => 'Nasi Uduk', 'sku' => 'FD-008', 'cost_price' => 8000, 'selling_price' => 15000, 'category' => 'food'],
            ['name' => 'Rendang', 'sku' => 'FD-009', 'cost_price' => 25000, 'selling_price' => 40000, 'category' => 'food'],
            ['name' => 'Ikan Bakar', 'sku' => 'FD-010', 'cost_price' => 22000, 'selling_price' => 38000, 'category' => 'food'],
            // Beverages
            ['name' => 'Es Teh Manis', 'sku' => 'BV-001', 'cost_price' => 2000, 'selling_price' => 5000, 'category' => 'beverages'],
            ['name' => 'Es Jeruk', 'sku' => 'BV-002', 'cost_price' => 4000, 'selling_price' => 8000, 'category' => 'beverages'],
            ['name' => 'Kopi Susu', 'sku' => 'BV-003', 'cost_price' => 8000, 'selling_price' => 15000, 'category' => 'beverages'],
            ['name' => 'Air Mineral', 'sku' => 'BV-004', 'cost_price' => 2000, 'selling_price' => 5000, 'category' => 'beverages'],
            ['name' => 'Jus Alpukat', 'sku' => 'BV-005', 'cost_price' => 10000, 'selling_price' => 18000, 'category' => 'beverages'],
            ['name' => 'Es Campur', 'sku' => 'BV-006', 'cost_price' => 8000, 'selling_price' => 15000, 'category' => 'beverages'],
            ['name' => 'Teh Tarik', 'sku' => 'BV-007', 'cost_price' => 6000, 'selling_price' => 12000, 'category' => 'beverages'],
            // Snacks
            ['name' => 'Kentang Goreng', 'sku' => 'SN-001', 'cost_price' => 8000, 'selling_price' => 15000, 'category' => 'snacks'],
            ['name' => 'Pisang Goreng', 'sku' => 'SN-002', 'cost_price' => 5000, 'selling_price' => 10000, 'category' => 'snacks'],
            ['name' => 'Tahu Crispy', 'sku' => 'SN-003', 'cost_price' => 4000, 'selling_price' => 8000, 'category' => 'snacks'],
            ['name' => 'Tempe Mendoan', 'sku' => 'SN-004', 'cost_price' => 4000, 'selling_price' => 8000, 'category' => 'snacks'],
            // Desserts
            ['name' => 'Es Krim', 'sku' => 'DS-001', 'cost_price' => 5000, 'selling_price' => 12000, 'category' => 'desserts'],
            ['name' => 'Pudding', 'sku' => 'DS-002', 'cost_price' => 4000, 'selling_price' => 10000, 'category' => 'desserts'],
            ['name' => 'Kue Lapis', 'sku' => 'DS-003', 'cost_price' => 6000, 'selling_price' => 12000, 'category' => 'desserts'],
        ];

        foreach ($products as $product) {
            $category = ProductCategory::where('slug', $product['category'])->first();

            Product::create([
                'sku' => $product['sku'],
                'name' => $product['name'],
                'barcode' => fake()->ean13(),
                'category_id' => $category?->id,
                'cost_price' => $product['cost_price'],
                'selling_price' => $product['selling_price'],
                'unit' => 'pcs',
                'stock_quantity' => rand(20, 100),
                'min_stock_level' => 5,
                'is_active' => true,
                'track_inventory' => true,
            ]);
        }
    }
}
