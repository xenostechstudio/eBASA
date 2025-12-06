<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\PriceList;
use App\Models\Product;
use Illuminate\Database\Seeder;

class PriceListSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $products = Product::where('is_active', true)->get();

        if ($branches->isEmpty() || $products->isEmpty()) {
            $this->command->warn('No branches or products found. Skipping PriceListSeeder.');
            return;
        }

        $priceListTemplates = [
            [
                'name' => 'Harga Retail',
                'type' => 'retail',
                'description' => 'Daftar harga standar untuk pelanggan retail.',
                'discount_percent' => 0,
                'is_default' => true,
                'priority' => 0,
            ],
            [
                'name' => 'Harga Grosir',
                'type' => 'wholesale',
                'description' => 'Harga khusus untuk pembelian grosir dengan minimum order.',
                'discount_percent' => 15,
                'is_default' => false,
                'priority' => 10,
                'min_qty' => 10,
            ],
            [
                'name' => 'Harga Member',
                'type' => 'member',
                'description' => 'Harga spesial untuk member terdaftar.',
                'discount_percent' => 10,
                'is_default' => false,
                'priority' => 5,
            ],
            [
                'name' => 'Promo Akhir Tahun',
                'type' => 'promo',
                'description' => 'Promo spesial akhir tahun dengan diskon besar.',
                'discount_percent' => 25,
                'is_default' => false,
                'priority' => 20,
                'valid_until' => true,
            ],
            [
                'name' => 'Harga Karyawan',
                'type' => 'custom',
                'description' => 'Harga khusus untuk karyawan internal.',
                'discount_percent' => 20,
                'is_default' => false,
                'priority' => 15,
            ],
        ];

        foreach ($branches as $branch) {
            $priceListCount = 1;

            foreach ($priceListTemplates as $template) {
                // Skip promo for some branches
                if ($template['type'] === 'promo' && rand(0, 10) > 5) {
                    continue;
                }

                $priceList = PriceList::create([
                    'branch_id' => $branch->id,
                    'code' => strtoupper($branch->code ?? 'BR') . '-PL-' . str_pad($priceListCount, 4, '0', STR_PAD_LEFT),
                    'name' => $template['name'],
                    'description' => $template['description'],
                    'type' => $template['type'],
                    'priority' => $template['priority'],
                    'valid_from' => now()->subDays(rand(0, 30)),
                    'valid_until' => isset($template['valid_until']) ? now()->addDays(rand(30, 60)) : null,
                    'is_active' => true,
                    'is_default' => $template['is_default'],
                ]);

                // Add products to price list
                $priceListProducts = $products->random(
                    max(1, (int) ($products->count() * (0.5 + (rand(0, 50) / 100))))
                );

                foreach ($priceListProducts as $product) {
                    $discountPercent = $template['discount_percent'] + rand(-5, 5);
                    $discountPercent = max(0, min(50, $discountPercent)); // Clamp between 0-50%

                    $price = $product->selling_price * (1 - ($discountPercent / 100));

                    $priceList->items()->create([
                        'product_id' => $product->id,
                        'price' => round($price, -2), // Round to nearest 100
                        'discount_percent' => $discountPercent,
                        'min_qty' => $template['min_qty'] ?? 1,
                    ]);
                }

                $priceListCount++;
            }
        }

        $this->command->info('Price lists seeded successfully.');
    }
}
