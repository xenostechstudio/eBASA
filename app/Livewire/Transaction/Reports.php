<?php

namespace App\Livewire\Transaction;

use App\Models\Transaction;
use App\Support\TransactionNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Reports extends Component
{
    public function render()
    {
        $activeBranchId = (int) session('active_branch_id', 0);
        $activeBranchId = $activeBranchId > 0 ? $activeBranchId : null;

        $stats = [
            'totalRevenue' => Transaction::when($activeBranchId, function ($query) use ($activeBranchId) {
                    $query->where('branch_id', $activeBranchId);
                })
                ->where('status', 'completed')
                ->sum('total_amount'),
            'totalTransactions' => Transaction::when($activeBranchId, function ($query) use ($activeBranchId) {
                    $query->where('branch_id', $activeBranchId);
                })
                ->count(),
            'avgTransaction' => Transaction::when($activeBranchId, function ($query) use ($activeBranchId) {
                    $query->where('branch_id', $activeBranchId);
                })
                ->where('status', 'completed')
                ->avg('total_amount') ?? 0,
        ];

        return view('livewire.transaction.reports', [
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Reports',
            'pageTagline' => 'Transaction Module',
            'activeModule' => 'transactions',
            'navLinks' => TransactionNavigation::links('reports'),
        ]);
    }
}
