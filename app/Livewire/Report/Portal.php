<?php

namespace App\Livewire\Report;

use App\Support\ReportNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Portal extends Component
{
    public function render()
    {
        $stats = [
            'totalRevenue' => 450000000,
            'totalOrders' => 1250,
            'avgOrderValue' => 360000,
            'growthRate' => 12.5,
        ];

        $quickLinks = [
            ['label' => 'Daily Sales', 'href' => route('reports.sales.daily'), 'icon' => 'heroicon-o-calendar-days', 'description' => 'Daily breakdown'],
            ['label' => 'Product Sales', 'href' => route('reports.sales.products'), 'icon' => 'heroicon-o-shopping-bag', 'description' => 'By product'],
            ['label' => 'Branch Performance', 'href' => route('reports.sales.branches'), 'icon' => 'heroicon-o-building-storefront', 'description' => 'By location'],
            ['label' => 'Stock Summary', 'href' => route('reports.inventory.stock'), 'icon' => 'heroicon-o-cube', 'description' => 'Inventory levels'],
            ['label' => 'Revenue', 'href' => route('reports.financial.revenue'), 'icon' => 'heroicon-o-chart-bar', 'description' => 'Income analysis'],
            ['label' => 'Expenses', 'href' => route('reports.financial.expenses'), 'icon' => 'heroicon-o-receipt-percent', 'description' => 'Cost tracking'],
        ];

        $recentReports = [
            ['name' => 'Monthly Sales Report', 'type' => 'Sales', 'generated' => now()->subHours(2)->diffForHumans()],
            ['name' => 'Stock Valuation', 'type' => 'Inventory', 'generated' => now()->subHours(5)->diffForHumans()],
            ['name' => 'Branch Comparison', 'type' => 'Performance', 'generated' => now()->subDay()->diffForHumans()],
        ];

        return view('livewire.report.portal', [
            'stats' => $stats,
            'quickLinks' => $quickLinks,
            'recentReports' => $recentReports,
        ])->layoutData([
            'pageTitle' => 'Reports & Analytics',
            'pageTagline' => 'Business Insights',
            'activeModule' => 'reports',
            'navLinks' => ReportNavigation::links('overview'),
        ]);
    }
}
