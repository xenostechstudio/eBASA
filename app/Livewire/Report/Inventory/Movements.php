<?php

namespace App\Livewire\Report\Inventory;

use App\Support\ReportNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Movements extends Component
{
    public function render()
    {
        $data = collect([
            ['date' => now()->subDays(1)->format('M d'), 'type' => 'in', 'product' => 'Product A', 'quantity' => 100, 'reference' => 'GR-001'],
            ['date' => now()->subDays(2)->format('M d'), 'type' => 'out', 'product' => 'Product B', 'quantity' => 50, 'reference' => 'SO-001'],
            ['date' => now()->subDays(3)->format('M d'), 'type' => 'transfer', 'product' => 'Product C', 'quantity' => 25, 'reference' => 'TRF-001'],
        ]);

        return view('livewire.report.inventory.movements', [
            'data' => $data,
            'stats' => ['totalIn' => 500, 'totalOut' => 320, 'totalTransfers' => 45],
        ])->layoutData([
            'pageTitle' => 'Movement History',
            'pageTagline' => 'Reports · Inventory',
            'activeModule' => 'reports',
            'navLinks' => ReportNavigation::links('inventory', 'movements'),
        ]);
    }
}
