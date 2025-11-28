<?php

namespace App\Livewire\Report\Inventory;

use App\Support\ReportNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Stock extends Component
{
    public function render()
    {
        $data = collect([
            ['name' => 'Product A', 'sku' => 'SKU-001', 'quantity' => 450, 'value' => 22500000, 'status' => 'in_stock'],
            ['name' => 'Product B', 'sku' => 'SKU-002', 'quantity' => 25, 'value' => 1500000, 'status' => 'low_stock'],
            ['name' => 'Product C', 'sku' => 'SKU-003', 'quantity' => 0, 'value' => 0, 'status' => 'out_of_stock'],
        ]);

        return view('livewire.report.inventory.stock', [
            'data' => $data,
            'stats' => ['totalProducts' => 150, 'totalValue' => $data->sum('value'), 'lowStock' => 12, 'outOfStock' => 5],
        ])->layoutData([
            'pageTitle' => 'Stock Summary',
            'pageTagline' => 'Reports · Inventory',
            'activeModule' => 'reports',
            'navLinks' => ReportNavigation::links('inventory', 'stock'),
        ]);
    }
}
