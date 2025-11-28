<?php

namespace App\Livewire\Report\Sales;

use App\Support\ReportNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Products extends Component
{
    public string $search = '';
    public string $categoryFilter = 'all';

    public function render()
    {
        $data = collect([
            ['name' => 'Product A', 'sku' => 'SKU-001', 'category' => 'Category 1', 'quantity' => 450, 'revenue' => 22500000],
            ['name' => 'Product B', 'sku' => 'SKU-002', 'category' => 'Category 2', 'quantity' => 320, 'revenue' => 19200000],
            ['name' => 'Product C', 'sku' => 'SKU-003', 'category' => 'Category 1', 'quantity' => 280, 'revenue' => 14000000],
        ]);

        return view('livewire.report.sales.products', [
            'data' => $data,
            'stats' => ['totalProducts' => 150, 'totalRevenue' => $data->sum('revenue'), 'totalQuantity' => $data->sum('quantity')],
        ])->layoutData([
            'pageTitle' => 'Product Sales Report',
            'pageTagline' => 'Reports · Sales',
            'activeModule' => 'reports',
            'navLinks' => ReportNavigation::links('sales', 'products'),
        ]);
    }
}
