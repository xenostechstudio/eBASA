<?php

namespace App\Livewire\Transaction;

use App\Models\CashierShift;
use App\Models\Transaction;
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

    public int $perPage = 15;

    public array $perPageOptions = [15, 30, 50];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage($value): void
    {
        $value = (int) $value;
        $this->perPage = in_array($value, $this->perPageOptions, true) ? $value : 15;
        $this->resetPage();
    }

    public function render()
    {
        $activeBranchId = (int) session('active_branch_id', 0);
        $activeBranchId = $activeBranchId > 0 ? $activeBranchId : null;

        $settlements = CashierShift::with(['cashier', 'branch'])
            ->when($activeBranchId, function ($query) use ($activeBranchId) {
                $query->where('branch_id', $activeBranchId);
            })
            ->where('status', 'closed')
            ->when($this->search, function ($query) {
                $query->whereHas('cashier', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('closed_at', 'desc')
            ->paginate($this->perPage);

        $stats = [
            'totalSettlements' => CashierShift::when($activeBranchId, function ($query) use ($activeBranchId) {
                    $query->where('branch_id', $activeBranchId);
                })
                ->where('status', 'closed')
                ->count(),
            'todaySettlements' => CashierShift::when($activeBranchId, function ($query) use ($activeBranchId) {
                    $query->where('branch_id', $activeBranchId);
                })
                ->where('status', 'closed')
                ->whereDate('closed_at', today())
                ->count(),
            'totalCashCollected' => CashierShift::when($activeBranchId, function ($query) use ($activeBranchId) {
                    $query->where('branch_id', $activeBranchId);
                })
                ->where('status', 'closed')
                ->sum('closing_cash'),
        ];

        return view('livewire.transaction.settlements', [
            'settlements' => $settlements,
            'stats' => $stats,
            'perPageOptions' => $this->perPageOptions,
        ])->layoutData([
            'pageTitle' => 'Settlements',
            'pageTagline' => 'Transaction Module',
            'activeModule' => 'transactions',
            'navLinks' => TransactionNavigation::links('settlements'),
        ]);
    }
}
