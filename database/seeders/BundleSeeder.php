<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Bundle;
use App\Models\Product;
use Illuminate\Database\Seeder;

class BundleSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $products = Product::where('is_active', true)->get();

        if ($branches->isEmpty() || $products->count() < 3) {
            $this->command->warn('Not enough branches or products. Skipping BundleSeeder.');
            return;
        }

        $bundleTemplates = [
            [
                'name' => 'Paket Hemat Makan Siang',
                'description' => 'Paket lengkap untuk makan siang dengan harga hemat.',
                'discount_percent' => 15,
                'product_count' => 3,
            ],
            [
                'name' => 'Paket Keluarga',
                'description' => 'Paket makanan untuk keluarga dengan porsi besar.',
                'discount_percent' => 20,
                'product_count' => 4,
            ],
            [
                'name' => 'Paket Snack Time',
                'description' => 'Paket camilan dan minuman untuk waktu santai.',
                'discount_percent' => 10,
                'product_count' => 3,
            ],
            [
                'name' => 'Paket Dessert Combo',
                'description' => 'Kombinasi dessert dan minuman segar.',
                'discount_percent' => 12,
                'product_count' => 2,
            ],
            [
                'name' => 'Paket Spesial Weekend',
                'description' => 'Paket spesial untuk akhir pekan.',
                'discount_percent' => 18,
                'product_count' => 5,
            ],
        ];

        foreach ($branches as $branch) {
            $bundleCount = 1;

            foreach ($bundleTemplates as $template) {
                // 70% chance to create each bundle for each branch
                if (rand(0, 10) > 7) {
                    continue;
                }

                $bundleProducts = $products->random(min($products->count(), $template['product_count']));

                // Calculate prices
                $originalPrice = 0;
                $items = [];

                foreach ($bundleProducts as $product) {
                    $quantity = rand(1, 2);
                    $unitPrice = $product->selling_price;
                    $originalPrice += $unitPrice * $quantity;

                    $items[] = [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'subtotal' => $unitPrice * $quantity,
                    ];
                }

                $discountAmount = $originalPrice * ($template['discount_percent'] / 100);
                $bundlePrice = $originalPrice - $discountAmount;

                $bundle = Bundle::create([
                    'branch_id' => $branch->id,
                    'sku' => strtoupper($branch->code ?? 'BR') . '-BDL-' . str_pad($bundleCount, 4, '0', STR_PAD_LEFT),
                    'name' => $template['name'],
                    'description' => $template['description'],
                    'bundle_price' => round($bundlePrice, -2), // Round to nearest 100
                    'original_price' => $originalPrice,
                    'discount_amount' => round($discountAmount, -2),
                    'discount_percent' => $template['discount_percent'],
                    'valid_from' => now()->subDays(rand(0, 30)),
                    'valid_until' => rand(0, 10) > 3 ? now()->addDays(rand(30, 90)) : null,
                    'is_active' => rand(0, 10) > 2, // 80% active
                ]);

                foreach ($items as $item) {
                    $bundle->items()->create($item);
                }

                $bundleCount++;
            }
        }

        $this->command->info('Bundles seeded successfully.');
    }
}
