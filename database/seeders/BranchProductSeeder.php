<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Product;
use Illuminate\Database\Seeder;

class BranchProductSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $products = Product::where('is_active', true)->get();

        if ($branches->isEmpty() || $products->isEmpty()) {
            $this->command->warn('No branches or products found. Skipping BranchProductSeeder.');
            return;
        }

        foreach ($branches as $branch) {
            // Each branch gets 70-100% of products
            $branchProducts = $products->random(
                max(1, (int) ($products->count() * (0.7 + (rand(0, 30) / 100))))
            );

            foreach ($branchProducts as $product) {
                // Check if already exists
                if (BranchProduct::where('branch_id', $branch->id)
                    ->where('product_id', $product->id)
                    ->exists()) {
                    continue;
                }

                // Random price adjustment (-10% to +15%)
                $priceAdjustment = 1 + (rand(-10, 15) / 100);
                $sellingPrice = round($product->selling_price * $priceAdjustment, -2); // Round to nearest 100

                BranchProduct::create([
                    'branch_id' => $branch->id,
                    'product_id' => $product->id,
                    'selling_price' => $sellingPrice,
                    'cost_price' => $product->cost_price,
                    'stock_quantity' => rand(10, 150),
                    'min_stock_level' => rand(5, 15),
                    'max_stock_level' => rand(100, 200),
                    'is_available' => rand(0, 10) > 1, // 90% available
                    'is_featured' => rand(0, 10) > 8, // 20% featured
                    'sort_order' => rand(1, 100),
                ]);
            }
        }

        $this->command->info('Branch products seeded successfully.');
    }
}
