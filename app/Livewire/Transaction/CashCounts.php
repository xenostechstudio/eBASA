<?php

namespace App\Livewire\Transaction;

use App\Models\CashierShift;
use App\Support\TransactionNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
class CashCounts extends Component
{
    use WithPagination;

    public function render()
    {
        $cashCounts = CashierShift::with(['cashier', 'branch'])
            ->whereNotNull('actual_cash')
            ->orderBy('closed_at', 'desc')
            ->paginate(15);

        $stats = [
            'totalCounted' => CashierShift::whereNotNull('actual_cash')->count(),
            'discrepancies' => CashierShift::whereNotNull('actual_cash')
                ->whereRaw('actual_cash != expected_cash')
                ->count(),
        ];

        return view('livewire.transaction.cash-counts', [
            'cashCounts' => $cashCounts,
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Cash Counts',
            'pageTagline' => 'Transaction Module',
            'activeModule' => 'transactions',
            'navLinks' => TransactionNavigation::links('cash-counts'),
        ]);
    }
}
