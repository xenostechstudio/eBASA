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

    public function updatedStatusFilter(): void
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
        $this->statusFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function export(string $format = 'excel'): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
    {
        $activeBranchId = (int) session('active_branch_id', 0);
        $activeBranchId = $activeBranchId > 0 ? $activeBranchId : null;

        $shifts = CashierShift::with(['cashier', 'branch'])
            ->withCount('transactions')
            ->when($activeBranchId, fn($q) => $q->where('branch_id', $activeBranchId))
            ->when($this->search, fn($q) => $q->whereHas('cashier', fn($sub) => $sub->where('name', 'like', '%' . $this->search . '%')))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->dateFrom, fn($q) => $q->whereDate('opened_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('opened_at', '<=', $this->dateTo))
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'shifts-' . now()->format('Y-m-d-His');

        if ($format === 'pdf') {
            // For now, return CSV as PDF export requires additional setup
            $filename .= '.csv';
        } else {
            $filename .= '.csv';
        }

        return response()->streamDownload(function () use ($shifts) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Cashier', 'Branch', 'Status', 'Opened At', 'Closed At', 'Opening Cash', 'Closing Cash', 'Transactions']);

            foreach ($shifts as $shift) {
                fputcsv($handle, [
                    $shift->cashier?->name ?? '-',
                    $shift->branch?->name ?? '-',
                    ucfirst($shift->status),
                    $shift->opened_at?->format('Y-m-d H:i:s') ?? '-',
                    $shift->closed_at?->format('Y-m-d H:i:s') ?? '-',
                    $shift->opening_cash ?? 0,
                    $shift->closing_cash ?? 0,
                    $shift->transactions_count ?? 0,
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function render()
    {
        $activeBranchId = (int) session('active_branch_id', 0);
        $activeBranchId = $activeBranchId > 0 ? $activeBranchId : null;

        $shifts = CashierShift::with(['cashier', 'branch'])
            ->withCount('transactions')
            ->when($activeBranchId, function ($query) use ($activeBranchId) {
                $query->where('branch_id', $activeBranchId);
            })
            ->when($this->search, function ($query) {
                $query->whereHas('cashier', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('opened_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('opened_at', '<=', $this->dateTo);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $stats = [
            'openShifts' => CashierShift::when($activeBranchId, function ($query) use ($activeBranchId) {
                    $query->where('branch_id', $activeBranchId);
                })
                ->where('status', 'open')
                ->count(),
            'closedToday' => CashierShift::when($activeBranchId, function ($query) use ($activeBranchId) {
                    $query->where('branch_id', $activeBranchId);
                })
                ->where('status', 'closed')
                ->whereDate('closed_at', today())
                ->count(),
            'totalShifts' => CashierShift::when($activeBranchId, function ($query) use ($activeBranchId) {
                    $query->where('branch_id', $activeBranchId);
                })
                ->count(),
        ];

        return view('livewire.transaction.shifts', [
            'shifts' => $shifts,
            'stats' => $stats,
            'perPageOptions' => $this->perPageOptions,
        ])->layoutData([
            'pageTitle' => 'Cashier Shifts',
            'pageTagline' => 'Transaction Module',
            'activeModule' => 'transactions',
            'navLinks' => TransactionNavigation::links('shifts'),
        ]);
    }
}
