<?php

namespace App\Livewire\Pos;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.pos')]
class Screen extends Component
{
    public function render(): View
    {
        $shiftSummary = [
            'branch' => 'BASA Mart – Tegal',
            'cashier' => 'Ayu Pratama',
            'since' => '08:00 WIB',
            'sales' => 1520000,
            'transactions' => 37,
            'cashOnHand' => 450000,
        ];

        $quickActions = [
            ['label' => 'Suspend Sale', 'icon' => 'pause'],
            ['label' => 'No Sale Receipt', 'icon' => 'receipt'],
            ['label' => 'Open Drawer', 'icon' => 'archive'],
        ];

        $cartItems = [
            [
                'name' => 'Indomie Goreng Fried Noodles',
                'sku' => 'SKU-IND-001',
                'qty' => 3,
                'price' => 3500,
                'total' => 10500,
                'badges' => ['Best Seller'],
                'status' => 'Low stock',
            ],
            [
                'name' => 'Indomie Kari Ayam Noodles',
                'sku' => 'SKU-IND-002',
                'qty' => 5,
                'price' => 3400,
                'total' => 17000,
                'badges' => [],
                'status' => null,
            ],
            [
                'name' => 'ABC Sambal Extra Pedas 135ml',
                'sku' => 'SKU-ABC-221',
                'qty' => 1,
                'price' => 12800,
                'total' => 12800,
                'badges' => ['Promo'],
                'status' => null,
            ],
            [
                'name' => 'ABC Kecap Manis 135ml',
                'sku' => 'SKU-ABC-045',
                'qty' => 2,
                'price' => 9000,
                'total' => 18000,
                'badges' => [],
                'status' => null,
            ],
            [
                'name' => 'Ultra Milk Full Cream 1L',
                'sku' => 'SKU-MIL-908',
                'qty' => 2,
                'price' => 18500,
                'total' => 37000,
                'badges' => [],
                'status' => 'Price override pending',
            ],
            [
                'name' => 'Ultra Milk Chocolate 250ml',
                'sku' => 'SKU-MIL-432',
                'qty' => 4,
                'price' => 6500,
                'total' => 26000,
                'badges' => ['Chilled'],
                'status' => null,
            ],
            [
                'name' => 'Aqua Mineral Water 600ml',
                'sku' => 'SKU-AQA-600',
                'qty' => 6,
                'price' => 4000,
                'total' => 24000,
                'badges' => [],
                'status' => null,
            ],
            [
                'name' => 'Pocari Sweat 350ml',
                'sku' => 'SKU-POC-350',
                'qty' => 3,
                'price' => 8500,
                'total' => 25500,
                'badges' => ['Popular'],
                'status' => null,
            ],
            [
                'name' => 'Teh Botol Sosro Original',
                'sku' => 'SKU-TBS-001',
                'qty' => 4,
                'price' => 6500,
                'total' => 26000,
                'badges' => [],
                'status' => null,
            ],
            [
                'name' => 'Good Day Freeze Coffee 250ml',
                'sku' => 'SKU-GDY-250',
                'qty' => 2,
                'price' => 9000,
                'total' => 18000,
                'badges' => [],
                'status' => null,
            ],
            [
                'name' => 'Sari Roti Tawar Kupas',
                'sku' => 'SKU-ROT-101',
                'qty' => 1,
                'price' => 19500,
                'total' => 19500,
                'badges' => ['Fresh'],
                'status' => null,
            ],
            [
                'name' => 'Roti Aoka Cokelat',
                'sku' => 'SKU-ROT-202',
                'qty' => 3,
                'price' => 9500,
                'total' => 28500,
                'badges' => [],
                'status' => null,
            ],
            [
                'name' => 'SilverQueen Almond 65g',
                'sku' => 'SKU-SQ-065',
                'qty' => 2,
                'price' => 14500,
                'total' => 29000,
                'badges' => ['Impulse'],
                'status' => null,
            ],
            [
                'name' => 'Chitato Sapi Panggang 68g',
                'sku' => 'SKU-CHT-068',
                'qty' => 4,
                'price' => 9500,
                'total' => 38000,
                'badges' => ['Promo'],
                'status' => null,
            ],
            [
                'name' => 'Lays Rumput Laut 55g',
                'sku' => 'SKU-LAY-055',
                'qty' => 2,
                'price' => 8500,
                'total' => 17000,
                'badges' => [],
                'status' => null,
            ],
            [
                'name' => 'Dettol Handwash Refill 200ml',
                'sku' => 'SKU-DTL-200',
                'qty' => 1,
                'price' => 23500,
                'total' => 23500,
                'badges' => [],
                'status' => null,
            ],
            [
                'name' => 'Lifebuoy Body Wash 450ml',
                'sku' => 'SKU-LFB-450',
                'qty' => 1,
                'price' => 29500,
                'total' => 29500,
                'badges' => [],
                'status' => null,
            ],
            [
                'name' => 'Downy Softener 900ml',
                'sku' => 'SKU-DWN-900',
                'qty' => 1,
                'price' => 32500,
                'total' => 32500,
                'badges' => ['Promo'],
                'status' => null,
            ],
            [
                'name' => 'Sunlight Jeruk Nipis 755ml',
                'sku' => 'SKU-SLT-755',
                'qty' => 2,
                'price' => 17500,
                'total' => 35000,
                'badges' => [],
                'status' => null,
            ],
            [
                'name' => 'Bango Kecap Manis 520ml',
                'sku' => 'SKU-BNG-520',
                'qty' => 1,
                'price' => 29500,
                'total' => 29500,
                'badges' => ['Favorite'],
                'status' => null,
            ],
        ];

        $suspendedSales = [
            ['number' => '#1032', 'amount' => 120000, 'time' => '14:02', 'note' => 'Customer grabbing cash'],
            ['number' => '#1033', 'amount' => 45000, 'time' => '14:05', 'note' => 'Split payment'],
        ];

        $catalogTiles = [
            ['title' => 'Teh Botol Sosro', 'price' => 6500, 'badge' => 'Popular'],
            ['title' => 'Pocari Sweat 350ml', 'price' => 8500, 'badge' => 'Chilled'],
            ['title' => 'Downy Softener 900ml', 'price' => 32500, 'badge' => 'Promo'],
            ['title' => 'Roti Aoka Cokelat', 'price' => 9500, 'badge' => 'Fresh'],
            ['title' => 'Fresh Eggs 1 Doz', 'price' => 28500, 'badge' => 'Bulk'],
            ['title' => 'Bango Kecap 520ml', 'price' => 29500, 'badge' => 'Favorite'],
        ];

        $notifications = [
            ['type' => 'warning', 'message' => 'Inventory sync delayed 2 min'],
        ];

        $paymentSummary = [
            'subtotal' => 60300,
            'discount' => 5000,
            'tax' => 3015,
            'total' => 58315,
        ];

        $checkoutPromos = [
            ['label' => 'Member Weekend', 'description' => 'Automatic loyalty discount', 'amount' => -3500],
            ['label' => 'Buy 2 Snack Bundle', 'description' => 'Combo savings applied', 'amount' => -1500],
        ];

        $paymentMethods = [
            ['label' => 'Cash', 'status' => 'ready'],
            ['label' => 'QRIS', 'status' => 'ready'],
            ['label' => 'Debit', 'status' => 'ready'],
            ['label' => 'Split', 'status' => 'custom'],
        ];

        $splitPayments = [
            ['method' => 'Cash', 'amount' => 30000],
            ['method' => 'QRIS', 'amount' => 28315],
        ];

        $cashierTransactions = [
            ['number' => '#POS-2034', 'time' => '08:12', 'items' => 4, 'amount' => 78000, 'channel' => 'Cash'],
            ['number' => '#POS-2035', 'time' => '08:25', 'items' => 2, 'amount' => 42500, 'channel' => 'QRIS'],
            ['number' => '#POS-2036', 'time' => '08:41', 'items' => 6, 'amount' => 132400, 'channel' => 'Debit'],
            ['number' => '#POS-2037', 'time' => '08:55', 'items' => 1, 'amount' => 18500, 'channel' => 'Cash'],
            ['number' => '#POS-2038', 'time' => '09:07', 'items' => 5, 'amount' => 99800, 'channel' => 'QRIS'],
            ['number' => '#POS-2039', 'time' => '09:15', 'items' => 3, 'amount' => 61500, 'channel' => 'Cash'],
        ];

        /** @noinspection PhpUndefinedMethodInspection */
        return view('livewire.pos.screen', [
            'shiftSummary' => $shiftSummary,
            'quickActions' => $quickActions,
            'cartItems' => $cartItems,
            'suspendedSales' => $suspendedSales,
            'catalogTiles' => $catalogTiles,
            'notifications' => $notifications,
            'paymentSummary' => $paymentSummary,
            'checkoutPromos' => $checkoutPromos,
            'paymentMethods' => $paymentMethods,
            'splitPayments' => $splitPayments,
            'cashierTransactions' => $cashierTransactions,
        ])->layoutData([
            'pageTitle' => 'Point of Sale',
        ]);
    }
}
