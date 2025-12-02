<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'code' => 'SUP-0001',
                'name' => 'PT Sumber Makmur Jaya',
                'contact_name' => 'Budi Santoso',
                'email' => 'budi@sumbermakmu.co.id',
                'phone' => '021-5551234',
                'tax_number' => '01.234.567.8-901.000',
                'address' => 'Jl. Industri Raya No. 45, Kawasan Industri Pulogadung, Jakarta Timur 13920',
                'payment_terms' => 30,
                'is_active' => true,
                'notes' => 'Supplier utama untuk produk elektronik dan aksesoris.',
            ],
            [
                'code' => 'SUP-0002',
                'name' => 'CV Mitra Sejahtera',
                'contact_name' => 'Siti Rahayu',
                'email' => 'siti@mitrasejahtera.com',
                'phone' => '024-7654321',
                'tax_number' => '02.345.678.9-012.000',
                'address' => 'Jl. Pemuda No. 88, Semarang 50132',
                'payment_terms' => 14,
                'is_active' => true,
                'notes' => 'Supplier untuk kebutuhan ATK dan perlengkapan kantor.',
            ],
            [
                'code' => 'SUP-0003',
                'name' => 'UD Berkah Abadi',
                'contact_name' => 'Ahmad Wijaya',
                'email' => 'ahmad@berkah-abadi.id',
                'phone' => '0274-445566',
                'tax_number' => '03.456.789.0-123.000',
                'address' => 'Jl. Malioboro No. 123, Yogyakarta 55213',
                'payment_terms' => 7,
                'is_active' => true,
                'notes' => 'Supplier lokal untuk produk kerajinan dan souvenir.',
            ],
            [
                'code' => 'SUP-0004',
                'name' => 'PT Global Distribusi Indonesia',
                'contact_name' => 'Dewi Lestari',
                'email' => 'dewi@globaldist.co.id',
                'phone' => '031-8889999',
                'tax_number' => '04.567.890.1-234.000',
                'address' => 'Jl. Rungkut Industri III No. 56, Surabaya 60293',
                'payment_terms' => 45,
                'is_active' => true,
                'notes' => 'Distributor nasional untuk berbagai kategori produk.',
            ],
            [
                'code' => 'SUP-0005',
                'name' => 'CV Aneka Jaya Mandiri',
                'contact_name' => 'Hendra Kusuma',
                'email' => 'hendra@anekajaya.com',
                'phone' => '022-6667788',
                'tax_number' => '05.678.901.2-345.000',
                'address' => 'Jl. Soekarno-Hatta No. 789, Bandung 40286',
                'payment_terms' => 30,
                'is_active' => false,
                'notes' => 'Supplier tidak aktif - kontrak berakhir.',
            ],
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::create($supplierData);
        }

        // Assign products to suppliers
        $this->assignProductsToSuppliers();

        // Create sample purchase orders
        $this->createSamplePurchaseOrders();
    }

    protected function assignProductsToSuppliers(): void
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            return;
        }

        $suppliers = Supplier::where('is_active', true)->get();

        foreach ($suppliers as $supplier) {
            // Each supplier gets 3-8 random products
            $supplierProducts = $products->random(min($products->count(), rand(3, 8)));

            foreach ($supplierProducts as $product) {
                // Check if already attached
                if ($supplier->products()->where('products.id', $product->id)->exists()) {
                    continue;
                }

                $supplier->products()->attach($product->id, [
                    'supplier_price' => $product->cost_price * (0.85 + (rand(0, 20) / 100)), // 85-105% of cost
                    'supplier_sku' => strtoupper(substr($supplier->code, -4)) . '-' . $product->sku,
                    'lead_time_days' => rand(3, 14),
                    'min_order_qty' => rand(1, 5) * 5, // 5, 10, 15, 20, 25
                    'is_preferred' => rand(0, 10) > 7, // 30% chance to be preferred
                ]);
            }
        }
    }

    protected function createSamplePurchaseOrders(): void
    {
        $suppliers = Supplier::with('products')->where('is_active', true)->get();

        if ($suppliers->isEmpty()) {
            return;
        }

        $warehouses = \App\Models\Warehouse::all();
        if ($warehouses->isEmpty()) {
            return;
        }

        $statuses = [
            PurchaseOrder::STATUS_DRAFT,
            PurchaseOrder::STATUS_APPROVED,
            PurchaseOrder::STATUS_PARTIALLY_RECEIVED,
            PurchaseOrder::STATUS_RECEIVED,
        ];

        foreach ($suppliers->take(3) as $supplier) {
            if ($supplier->products->isEmpty()) {
                continue;
            }

            // Create 2-4 POs per supplier
            $poCount = rand(2, 4);

            for ($i = 0; $i < $poCount; $i++) {
                $status = $statuses[array_rand($statuses)];
                $orderDate = now()->subDays(rand(1, 60));

                $po = PurchaseOrder::create([
                    'reference' => PurchaseOrder::generateReference(),
                    'supplier_id' => $supplier->id,
                    'warehouse_id' => $warehouses->random()->id,
                    'order_date' => $orderDate,
                    'expected_delivery_date' => $orderDate->copy()->addDays(rand(7, 21)),
                    'status' => $status,
                    'payment_terms' => $supplier->payment_terms . '_days',
                    'requested_by' => 'System Seeder',
                    'notes' => 'Auto-generated sample PO',
                ]);

                // Add 2-5 items
                $itemCount = rand(2, 5);
                $products = $supplier->products->random(min($supplier->products->count(), $itemCount));

                foreach ($products as $product) {
                    $quantity = rand(10, 100);
                    $unitPrice = $product->pivot->supplier_price ?? $product->cost_price;
                    $subtotal = $quantity * $unitPrice;

                    $receivedQty = 0;
                    if ($status === PurchaseOrder::STATUS_RECEIVED) {
                        $receivedQty = $quantity;
                    } elseif ($status === PurchaseOrder::STATUS_PARTIALLY_RECEIVED) {
                        $receivedQty = rand(1, $quantity - 1);
                    }

                    PurchaseOrderItem::create([
                        'purchase_order_id' => $po->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'received_quantity' => $receivedQty,
                        'unit_price' => $unitPrice,
                        'tax_rate' => 0,
                        'tax_amount' => 0,
                        'subtotal' => $subtotal,
                        'total' => $subtotal,
                    ]);
                }

                $po->recalculateTotals();
            }
        }
    }
}
