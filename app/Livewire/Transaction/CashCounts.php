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

    #[Url]
    public string $dateFrom = '';

    #[Url]
    public string $dateTo = '';

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

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage($value): void
    {
        $value = (int) $value;
        $this->perPage = in_array($value, $this->perPageOptions, true) ? $value : 15;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->discrepancyFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function export(string $format = 'excel'): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
    {
        $activeBranchId = (int) session('active_branch_id', 0);
        $activeBranchId = $activeBranchId > 0 ? $activeBranchId : null;

        $cashCounts = CashierShift::with(['cashier', 'branch'])
            ->whereNotNull('closing_cash')
            ->when($activeBranchId, fn($q) => $q->where('branch_id', $activeBranchId))
            ->when($this->search, fn($q) => $q->where(function ($sub) {
                $sub->whereHas('cashier', fn($s) => $s->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('branch', fn($s) => $s->where('name', 'like', '%' . $this->search . '%'));
            }))
            ->when($this->discrepancyFilter === 'discrepancy', fn($q) => $q->whereColumn('closing_cash', '!=', 'expected_cash'))
            ->when($this->discrepancyFilter === 'balanced', fn($q) => $q->whereColumn('closing_cash', '=', 'expected_cash'))
            ->when($this->dateFrom, fn($q) => $q->whereDate('closed_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('closed_at', '<=', $this->dateTo))
            ->orderBy('closed_at', 'desc')
            ->get();

        $filename = 'cash-counts-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($cashCounts) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Cashier', 'Branch', 'Expected Cash', 'Actual Cash', 'Difference', 'Status', 'Counted At']);

            foreach ($cashCounts as $count) {
                $difference = ($count->closing_cash ?? 0) - ($count->expected_cash ?? 0);
                fputcsv($handle, [
                    $count->cashier?->name ?? '-',
                    $count->branch?->name ?? '-',
                    $count->expected_cash ?? 0,
                    $count->closing_cash ?? 0,
                    $difference,
                    $difference == 0 ? 'Balanced' : 'Discrepancy',
                    $count->closed_at?->format('Y-m-d H:i:s') ?? '-',
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
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
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('closed_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('closed_at', '<=', $this->dateTo);
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
