<?php

namespace App\Livewire\Transaction;

use App\Models\CashierShift;
use App\Support\TransactionNavigation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
class CashCounts extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $discrepancyFilter = '';

    public int $perPage = 15;

    public array $perPageOptions = [15, 30, 50];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedDiscrepancyFilter(): void
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

        $cashCounts = CashierShift::with(['cashier', 'branch'])
            ->when($activeBranchId, function ($query) use ($activeBranchId) {
                $query->where('branch_id', $activeBranchId);
            })
            ->whereNotNull('closing_cash')
            ->when($this->search, function ($query) {
                $search = $this->search;

                $query->where(function ($q) use ($search) {
                    $q->whereHas('cashier', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('branch', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%' . $search . '%');
                    });
                });
            })
            ->when($this->discrepancyFilter === 'discrepancy', function ($query) {
                $query->whereColumn('closing_cash', '!=', 'expected_cash');
            })
            ->when($this->discrepancyFilter === 'balanced', function ($query) {
                $query->whereColumn('closing_cash', '=', 'expected_cash');
            })
            ->orderBy('closed_at', 'desc')
            ->paginate($this->perPage);

        $stats = [
            'totalCounted' => CashierShift::when($activeBranchId, function ($query) use ($activeBranchId) {
                    $query->where('branch_id', $activeBranchId);
                })
                ->whereNotNull('closing_cash')
                ->count(),
            'discrepancies' => CashierShift::when($activeBranchId, function ($query) use ($activeBranchId) {
                    $query->where('branch_id', $activeBranchId);
                })
                ->whereNotNull('closing_cash')
                ->whereColumn('closing_cash', '!=', 'expected_cash')
                ->count(),
        ];

        return view('livewire.transaction.cash-counts', [
            'cashCounts' => $cashCounts,
            'stats' => $stats,
            'perPageOptions' => $this->perPageOptions,
        ])->layoutData([
            'pageTitle' => 'Cash Counts',
            'pageTagline' => 'Transaction Module',
            'activeModule' => 'transactions',
            'navLinks' => TransactionNavigation::links('cash-counts'),
        ]);
    }
}
