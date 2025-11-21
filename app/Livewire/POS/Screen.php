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
                'name' => 'ABC Sambal Extra Pedas 135ml',
                'sku' => 'SKU-ABC-221',
                'qty' => 1,
                'price' => 12800,
                'total' => 12800,
                'badges' => ['Promo'],
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
            ['type' => 'success', 'message' => 'Barcode ready – scanner connected'],
            ['type' => 'warning', 'message' => 'Inventory sync delayed 2 min'],
        ];

        $paymentSummary = [
            'subtotal' => 60300,
            'discount' => 5000,
            'tax' => 3015,
            'total' => 58315,
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
        ])->layoutData([
            'pageTitle' => 'Point of Sale',
        ]);
    }
}
