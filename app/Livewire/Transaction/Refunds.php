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

    public function render()
    {
        $refunds = Transaction::with(['branch', 'cashier'])
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
            'totalRefunds' => Transaction::where('status', 'refunded')->count(),
            'totalRefundAmount' => Transaction::where('status', 'refunded')->sum('total_amount'),
            'todayRefunds' => Transaction::where('status', 'refunded')
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
