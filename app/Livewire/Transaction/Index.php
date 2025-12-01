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
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = '';

    #[Url]
    public string $paymentFilter = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPaymentFilter(): void
    {
        $this->resetPage();
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function setPaymentFilter(string $method): void
    {
        $this->paymentFilter = $method;
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function export(string $format): void
    {
        if (! in_array($format, ['excel', 'pdf'], true)) {
            return;
        }

        $label = strtoupper($format);

        session()->flash('flash', [
            'type' => 'info',
            'title' => $label . ' export',
            'message' => 'Export functionality is not implemented yet.',
        ]);
    }

    public function render()
    {
        // Stats
        $todaySales = Transaction::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        $completedCount = Transaction::where('status', 'completed')->count();
        $pendingCount = Transaction::where('status', 'pending')->count();
        $refundedCount = Transaction::where('status', 'refunded')->count();

        $openShifts = CashierShift::where('status', 'open')->count();

        // Transactions query
        $transactionsQuery = Transaction::with(['branch', 'cashier'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('transaction_code', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->paymentFilter, function ($query) {
                $query->where('payment_method', $this->paymentFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $transactions = $transactionsQuery->paginate(15);

        $statusOptions = [
            '' => 'All Status',
            'completed' => 'Completed',
            'pending' => 'Pending',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded',
        ];

        $paymentOptions = [
            '' => 'All Payments',
            'cash' => 'Cash',
            'card' => 'Card',
            'qris' => 'QRIS',
            'transfer' => 'Transfer',
            'mixed' => 'Mixed',
        ];

        return view('livewire.transaction.index', [
            'transactions' => $transactions,
            'todaySales' => $todaySales,
            'completedCount' => $completedCount,
            'pendingCount' => $pendingCount,
            'refundedCount' => $refundedCount,
            'openShifts' => $openShifts,
            'statusOptions' => $statusOptions,
            'paymentOptions' => $paymentOptions,
        ])->layoutData([
            'pageTitle' => 'Transactions',
            'pageTagline' => 'Transaction Module',
            'activeModule' => 'transactions',
            'navLinks' => TransactionNavigation::links('transactions'),
        ]);
    }
}
