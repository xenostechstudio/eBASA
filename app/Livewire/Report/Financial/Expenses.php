<?php

namespace App\Livewire\Report\Financial;

use App\Support\ReportNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Expenses extends Component
{
    public function render()
    {
        $data = collect([
            ['category' => 'Salaries', 'amount' => 45000000, 'percentage' => 45],
            ['category' => 'Rent', 'amount' => 15000000, 'percentage' => 15],
            ['category' => 'Utilities', 'amount' => 8000000, 'percentage' => 8],
            ['category' => 'Supplies', 'amount' => 12000000, 'percentage' => 12],
            ['category' => 'Other', 'amount' => 20000000, 'percentage' => 20],
        ]);

        return view('livewire.report.financial.expenses', [
            'data' => $data,
            'stats' => ['totalExpenses' => $data->sum('amount'), 'categories' => $data->count()],
        ])->layoutData([
            'pageTitle' => 'Expenses Report',
            'pageTagline' => 'Reports · Financial',
            'activeModule' => 'reports',
            'navLinks' => ReportNavigation::links('financial', 'expenses'),
        ]);
    }
}
