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
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

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
        ])->layoutData([
            'pageTitle' => 'Refunds',
            'pageTagline' => 'Transaction Module',
            'activeModule' => 'transactions',
            'navLinks' => TransactionNavigation::links('refunds'),
        ]);
    }
}
