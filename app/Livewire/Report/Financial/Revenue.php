<?php

namespace App\Livewire\Report\Financial;

use App\Support\ReportNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Revenue extends Component
{
    public function render()
    {
        $data = collect([
            ['month' => 'Jan', 'revenue' => 125000000, 'target' => 120000000],
            ['month' => 'Feb', 'revenue' => 135000000, 'target' => 130000000],
            ['month' => 'Mar', 'revenue' => 142000000, 'target' => 140000000],
        ]);

        return view('livewire.report.financial.revenue', [
            'data' => $data,
            'stats' => ['totalRevenue' => $data->sum('revenue'), 'totalTarget' => $data->sum('target'), 'achievement' => round($data->sum('revenue') / $data->sum('target') * 100, 1)],
        ])->layoutData([
            'pageTitle' => 'Revenue Report',
            'pageTagline' => 'Reports · Financial',
            'activeModule' => 'reports',
            'navLinks' => ReportNavigation::links('financial', 'revenue'),
        ]);
    }
}
