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
            ['label' => 'Cash', 'status' => 'ready', 'shortcut' => 'F7'],
            ['label' => 'QRIS', 'status' => 'ready', 'shortcut' => 'F8'],
            ['label' => 'Debit', 'status' => 'ready', 'shortcut' => 'F9'],
            ['label' => 'Split', 'status' => 'custom', 'shortcut' => 'F10'],
        ];

        $splitPayments = [
            ['method' => 'Cash', 'amount' => 30000],
            ['method' => 'QRIS', 'amount' => 28315],
        ];

        $cashierTransactions = [
            [
                'number' => '#POS-2034',
                'time' => '08:12',
                'items' => 4,
                'amount' => 78_000,
                'channel' => 'Cash',
                'receipt' => [
                    'items' => [
                        ['name' => 'Indomie Goreng', 'qty' => 2, 'price' => 3_500, 'total' => 7_000],
                        ['name' => 'Aqua 600ml', 'qty' => 4, 'price' => 4_000, 'total' => 16_000],
                        ['name' => 'SilverQueen 65g', 'qty' => 1, 'price' => 14_500, 'total' => 14_500],
                    ],
                    'subtotal' => 37_500,
                    'discount' => 2_500,
                    'tax' => 3_000,
                    'total' => 38_000,
                ],
            ],
            [
                'number' => '#POS-2035',
                'time' => '08:25',
                'items' => 2,
                'amount' => 42_500,
                'channel' => 'QRIS',
                'receipt' => [
                    'items' => [
                        ['name' => 'Pocari Sweat 350ml', 'qty' => 2, 'price' => 8_500, 'total' => 17_000],
                        ['name' => 'Downy Softener 900ml', 'qty' => 1, 'price' => 32_500, 'total' => 32_500],
                    ],
                    'subtotal' => 49_500,
                    'discount' => 7_000,
                    'tax' => 2_475,
                    'total' => 44_975,
                ],
            ],
            [
                'number' => '#POS-2036',
                'time' => '08:41',
                'items' => 6,
                'amount' => 132_400,
                'channel' => 'Debit',
                'receipt' => [
                    'items' => [
                        ['name' => 'Ultra Milk 1L', 'qty' => 3, 'price' => 18_500, 'total' => 55_500],
                        ['name' => 'Bango Kecap 520ml', 'qty' => 1, 'price' => 29_500, 'total' => 29_500],
                        ['name' => 'Lays Rumput Laut 55g', 'qty' => 4, 'price' => 8_500, 'total' => 34_000],
                    ],
                    'subtotal' => 119_000,
                    'discount' => 6_000,
                    'tax' => 11_900,
                    'total' => 124_900,
                ],
            ],
            [
                'number' => '#POS-2037',
                'time' => '08:55',
                'items' => 1,
                'amount' => 18_500,
                'channel' => 'Cash',
                'receipt' => [
                    'items' => [
                        ['name' => 'Lifebuoy Body Wash 450ml', 'qty' => 1, 'price' => 18_500, 'total' => 18_500],
                    ],
                    'subtotal' => 18_500,
                    'discount' => 0,
                    'tax' => 1_850,
                    'total' => 20_350,
                ],
            ],
            [
                'number' => '#POS-2038',
                'time' => '09:07',
                'items' => 5,
                'amount' => 99_800,
                'channel' => 'QRIS',
                'receipt' => [
                    'items' => [
                        ['name' => 'Teh Botol Sosro', 'qty' => 6, 'price' => 6_500, 'total' => 39_000],
                        ['name' => 'Indomie Kari Ayam', 'qty' => 5, 'price' => 3_400, 'total' => 17_000],
                        ['name' => 'ABC Sambal Extra Pedas', 'qty' => 2, 'price' => 12_800, 'total' => 25_600],
                    ],
                    'subtotal' => 81_600,
                    'discount' => 4_100,
                    'tax' => 8_160,
                    'total' => 85_660,
                ],
            ],
            [
                'number' => '#POS-2039',
                'time' => '09:15',
                'items' => 3,
                'amount' => 61_500,
                'channel' => 'Cash',
                'receipt' => [
                    'items' => [
                        ['name' => 'Sari Roti Tawar', 'qty' => 1, 'price' => 19_500, 'total' => 19_500],
                        ['name' => 'Good Day Freeze Coffee', 'qty' => 3, 'price' => 9_000, 'total' => 27_000],
                        ['name' => 'Dettol Handwash Refill', 'qty' => 1, 'price' => 23_500, 'total' => 23_500],
                    ],
                    'subtotal' => 70_000,
                    'discount' => 8_500,
                    'tax' => 7_000,
                    'total' => 68_500,
                ],
            ],
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
