<?php

namespace App\Livewire\Transaction;

use App\Models\CashierShift;
use App\Support\TransactionNavigation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
class Shifts extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = '';

    public function render()
    {
        $shifts = CashierShift::with(['cashier', 'branch'])
            ->when($this->search, function ($query) {
                $query->whereHas('cashier', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'openShifts' => CashierShift::where('status', 'open')->count(),
            'closedToday' => CashierShift::where('status', 'closed')
                ->whereDate('closed_at', today())
                ->count(),
            'totalShifts' => CashierShift::count(),
        ];

        return view('livewire.transaction.shifts', [
            'shifts' => $shifts,
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Cashier Shifts',
            'pageTagline' => 'Transaction Module',
            'activeModule' => 'transactions',
            'navLinks' => TransactionNavigation::links('shifts'),
        ]);
    }
}
