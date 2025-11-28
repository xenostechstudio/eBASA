<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use App\Models\Warehouse;
use App\Support\InventoryNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Portal extends Component
{
    public function render()
    {
        $stats = [
            'totalProducts' => Product::count(),
            'warehouses' => Warehouse::count(),
            'inStock' => Product::where('stock_quantity', '>', 0)->count(),
            'lowStock' => Product::whereColumn('stock_quantity', '<=', 'min_stock_level')
                ->where('stock_quantity', '>', 0)->count(),
        ];

        $recentActivities = [
            ['title' => 'Penerimaan barang Gudang Tegal', 'timestamp' => '10 minutes ago', 'type' => 'Inbound'],
            ['title' => 'Stock adjustment Pekalongan', 'timestamp' => '45 minutes ago', 'type' => 'Adjustment'],
            ['title' => 'Transfer Pemalang → Tegal', 'timestamp' => 'Today, 10:15', 'type' => 'Transfer'],
        ];

        $warehouseHealth = Warehouse::with('branch')->get()->map(fn ($w) => [
            'name' => $w->name,
            'branch' => $w->branch?->name ?? 'N/A',
            'fill' => rand(70, 95),
        ])->take(5)->toArray();

        $quickLinks = [
            ['label' => 'Stock Levels', 'href' => route('inventory.stock.levels'), 'icon' => 'heroicon-o-chart-bar', 'description' => 'View current stock'],
            ['label' => 'Adjustments', 'href' => route('inventory.stock.adjustments'), 'icon' => 'heroicon-o-adjustments-horizontal', 'description' => 'Manage adjustments'],
            ['label' => 'Transfers', 'href' => route('inventory.stock.transfers'), 'icon' => 'heroicon-o-arrows-right-left', 'description' => 'Inter-warehouse transfers'],
            ['label' => 'Products', 'href' => route('inventory.catalog.products'), 'icon' => 'heroicon-o-shopping-bag', 'description' => 'Product catalog'],
            ['label' => 'Bundles', 'href' => route('inventory.catalog.bundles'), 'icon' => 'heroicon-o-squares-2x2', 'description' => 'Product bundles'],
            ['label' => 'Price Lists', 'href' => route('inventory.catalog.price-lists'), 'icon' => 'heroicon-o-currency-dollar', 'description' => 'Pricing management'],
        ];

        return view('livewire.inventory.portal', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'warehouseHealth' => $warehouseHealth,
            'quickLinks' => $quickLinks,
        ])->layoutData([
            'pageTitle' => 'Inventory',
            'pageTagline' => 'Stock & Operations',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('overview'),
        ]);
    }
}
