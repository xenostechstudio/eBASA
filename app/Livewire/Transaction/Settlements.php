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
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function export(string $format = 'excel'): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
    {
        $activeBranchId = (int) session('active_branch_id', 0);
        $activeBranchId = $activeBranchId > 0 ? $activeBranchId : null;

        $settlements = CashierShift::with(['cashier', 'branch'])
            ->where('status', 'closed')
            ->when($activeBranchId, fn($q) => $q->where('branch_id', $activeBranchId))
            ->when($this->search, fn($q) => $q->whereHas('cashier', fn($sub) => $sub->where('name', 'like', '%' . $this->search . '%')))
            ->when($this->dateFrom, fn($q) => $q->whereDate('closed_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('closed_at', '<=', $this->dateTo))
            ->orderBy('closed_at', 'desc')
            ->get();

        $filename = 'settlements-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($settlements) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Cashier', 'Branch', 'Opened At', 'Closed At', 'Duration', 'Opening Cash', 'Expected Cash', 'Closing Cash', 'Difference']);

            foreach ($settlements as $settlement) {
                $difference = ($settlement->closing_cash ?? 0) - ($settlement->expected_cash ?? 0);
                $duration = $settlement->opened_at && $settlement->closed_at
                    ? $settlement->opened_at->diffForHumans($settlement->closed_at, true)
                    : '-';

                fputcsv($handle, [
                    $settlement->cashier?->name ?? '-',
                    $settlement->branch?->name ?? '-',
                    $settlement->opened_at?->format('Y-m-d H:i:s') ?? '-',
                    $settlement->closed_at?->format('Y-m-d H:i:s') ?? '-',
                    $duration,
                    $settlement->opening_cash ?? 0,
                    $settlement->expected_cash ?? 0,
                    $settlement->closing_cash ?? 0,
                    $difference,
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
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
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('closed_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('closed_at', '<=', $this->dateTo);
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
