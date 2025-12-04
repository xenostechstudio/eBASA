<?php

namespace App\Livewire\Transaction;

use App\Models\Transaction;
use App\Support\TransactionNavigation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
class Refunds extends Component
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

    public ?int $selectedTransactionId = null;

    public ?Transaction $selectedTransaction = null;

    public function viewTransaction(int $id): void
    {
        $this->selectedTransactionId = $id;
        $this->selectedTransaction = Transaction::with(['items', 'branch', 'cashier'])->find($id);
    }

    public function closeTransactionDetail(): void
    {
        $this->selectedTransactionId = null;
        $this->selectedTransaction = null;
    }

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

        $refunds = Transaction::with(['branch', 'cashier'])
            ->where('status', 'refunded')
            ->when($activeBranchId, fn($q) => $q->where('branch_id', $activeBranchId))
            ->when($this->search, fn($q) => $q->where(function ($sub) {
                $sub->where('transaction_code', 'like', '%' . $this->search . '%')
                    ->orWhere('customer_name', 'like', '%' . $this->search . '%');
            }))
            ->when($this->dateFrom, fn($q) => $q->whereDate('updated_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('updated_at', '<=', $this->dateTo))
            ->orderBy('updated_at', 'desc')
            ->get();

        $filename = 'refunds-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($refunds) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Transaction Code', 'Customer', 'Branch', 'Cashier', 'Amount', 'Payment Method', 'Refunded At']);

            foreach ($refunds as $refund) {
                fputcsv($handle, [
                    $refund->transaction_code,
                    $refund->customer_name ?? 'Walk-in',
                    $refund->branch?->name ?? '-',
                    $refund->cashier?->name ?? '-',
                    $refund->total_amount ?? 0,
                    $refund->payment_method_label ?? '-',
                    $refund->updated_at?->format('Y-m-d H:i:s') ?? '-',
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function render()
    {
        $activeBranchId = (int) session('active_branch_id', 0);
        $activeBranchId = $activeBranchId > 0 ? $activeBranchId : null;

        $refunds = Transaction::with(['branch', 'cashier'])
            ->when($activeBranchId, function ($query) use ($activeBranchId) {
                $query->where('branch_id', $activeBranchId);
            })
            ->where('status', 'refunded')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('transaction_code', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('updated_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('updated_at', '<=', $this->dateTo);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate($this->perPage);

        $stats = [
            'totalRefunds' => Transaction::when($activeBranchId, function ($query) use ($activeBranchId) {
                    $query->where('branch_id', $activeBranchId);
                })
                ->where('status', 'refunded')
                ->count(),
            'totalRefundAmount' => Transaction::when($activeBranchId, function ($query) use ($activeBranchId) {
                    $query->where('branch_id', $activeBranchId);
                })
                ->where('status', 'refunded')
                ->sum('total_amount'),
            'todayRefunds' => Transaction::when($activeBranchId, function ($query) use ($activeBranchId) {
                    $query->where('branch_id', $activeBranchId);
                })
                ->where('status', 'refunded')
                ->whereDate('updated_at', today())
                ->count(),
        ];

        return view('livewire.transaction.refunds', [
            'refunds' => $refunds,
            'stats' => $stats,
            'perPageOptions' => $this->perPageOptions,
        ])->layoutData([
            'pageTitle' => 'Refunds',
            'pageTagline' => 'Transaction Module',
            'activeModule' => 'transactions',
            'navLinks' => TransactionNavigation::links('refunds'),
        ]);
    }
}
