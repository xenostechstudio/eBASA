<?php

namespace App\Livewire\Report\Sales;

use App\Support\ReportNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Branches extends Component
{
    public function render()
    {
        $data = collect([
            ['name' => 'Pemalang', 'orders' => 450, 'revenue' => 125000000, 'growth' => 12.5],
            ['name' => 'Tegal', 'orders' => 380, 'revenue' => 98000000, 'growth' => 8.2],
            ['name' => 'Banjaran', 'orders' => 290, 'revenue' => 75000000, 'growth' => -2.1],
        ]);

        return view('livewire.report.sales.branches', [
            'data' => $data,
            'stats' => ['totalBranches' => 3, 'totalRevenue' => $data->sum('revenue'), 'avgGrowth' => $data->avg('growth')],
        ])->layoutData([
            'pageTitle' => 'Branch Performance',
            'pageTagline' => 'Reports · Sales',
            'activeModule' => 'reports',
            'navLinks' => ReportNavigation::links('sales', 'branches'),
        ]);
    }
}
