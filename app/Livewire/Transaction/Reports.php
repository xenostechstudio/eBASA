<?php

namespace App\Livewire\Transaction;

use App\Models\Transaction;
use App\Support\TransactionNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Reports extends Component
{
    public function render()
    {
        $stats = [
            'totalRevenue' => Transaction::where('status', 'completed')->sum('total_amount'),
            'totalTransactions' => Transaction::count(),
            'avgTransaction' => Transaction::where('status', 'completed')->avg('total_amount') ?? 0,
        ];

        return view('livewire.transaction.reports', [
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Reports',
            'pageTagline' => 'Transaction Module',
            'activeModule' => 'transactions',
            'navLinks' => TransactionNavigation::links('reports'),
        ]);
    }
}
