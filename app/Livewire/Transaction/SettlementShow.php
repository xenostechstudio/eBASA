<?php

namespace App\Livewire\Transaction;

use App\Models\CashierShift;
use App\Models\Transaction;
use App\Support\TransactionNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class SettlementShow extends Component
{
    public CashierShift $shift;

    public ?int $selectedTransactionId = null;

    public ?Transaction $selectedTransaction = null;

    public function mount(CashierShift $shift): void
    {
        $this->shift = $shift->load(['branch', 'cashier']);
    }

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

    public function render(): View
    {
        $transactions = Transaction::with(['items', 'cashier', 'branch'])
            ->where('shift_id', $this->shift->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totals = [
            'transactionCount' => $transactions->count(),
            'grossSales' => $transactions->sum('total_amount'),
            'cashSales' => $transactions->where('payment_method', 'cash')->sum('total_amount'),
            'nonCashSales' => $transactions->whereIn('payment_method', ['card', 'qris', 'transfer', 'mixed'])->sum('total_amount'),
        ];

        /** @noinspection PhpUndefinedMethodInspection */
        return view('livewire.transaction.settlement-show', [
            'shift' => $this->shift,
            'transactions' => $transactions,
            'totals' => $totals,
        ])->layoutData([
            'pageTitle' => 'Settlement Detail',
            'pageTagline' => 'Transaction Module',
            'activeModule' => 'transactions',
            'navLinks' => TransactionNavigation::links('settlements'),
        ]);
    }
}
