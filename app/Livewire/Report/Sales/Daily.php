<?php

namespace App\Livewire\Report\Sales;

use App\Support\ReportNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Daily extends Component
{
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $branchFilter = 'all';

    public function mount(): void
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function render()
    {
        $data = collect([
            ['date' => now()->subDays(6)->format('M d'), 'sales' => 12500000, 'orders' => 45],
            ['date' => now()->subDays(5)->format('M d'), 'sales' => 15200000, 'orders' => 52],
            ['date' => now()->subDays(4)->format('M d'), 'sales' => 11800000, 'orders' => 38],
            ['date' => now()->subDays(3)->format('M d'), 'sales' => 18500000, 'orders' => 61],
            ['date' => now()->subDays(2)->format('M d'), 'sales' => 14200000, 'orders' => 48],
            ['date' => now()->subDays(1)->format('M d'), 'sales' => 16800000, 'orders' => 55],
            ['date' => now()->format('M d'), 'sales' => 9500000, 'orders' => 32],
        ]);

        $stats = [
            'totalSales' => $data->sum('sales'),
            'totalOrders' => $data->sum('orders'),
            'avgDaily' => $data->avg('sales'),
            'bestDay' => $data->sortByDesc('sales')->first(),
        ];

        return view('livewire.report.sales.daily', [
            'data' => $data,
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Daily Sales Report',
            'pageTagline' => 'Reports · Sales',
            'activeModule' => 'reports',
            'navLinks' => ReportNavigation::links('sales', 'daily'),
        ]);
    }
}
