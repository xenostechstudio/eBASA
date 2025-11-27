<?php

namespace App\Livewire\Transaction;

use App\Models\CashierShift;
use App\Support\TransactionNavigation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
class Settlements extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public function render()
    {
        $settlements = CashierShift::with(['cashier', 'branch'])
            ->where('status', 'closed')
            ->when($this->search, function ($query) {
                $query->whereHas('cashier', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('closed_at', 'desc')
            ->paginate(15);

        $stats = [
            'totalSettlements' => CashierShift::where('status', 'closed')->count(),
            'todaySettlements' => CashierShift::where('status', 'closed')
                ->whereDate('closed_at', today())
                ->count(),
            'totalCashCollected' => CashierShift::where('status', 'closed')->sum('actual_cash'),
        ];

        return view('livewire.transaction.settlements', [
            'settlements' => $settlements,
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Settlements',
            'pageTagline' => 'Transaction Module',
            'activeModule' => 'transactions',
            'navLinks' => TransactionNavigation::links('settlements'),
        ]);
    }
}
