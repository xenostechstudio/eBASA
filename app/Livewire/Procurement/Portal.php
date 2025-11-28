<?php

namespace App\Livewire\Procurement;

use App\Support\ProcurementNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Portal extends Component
{
    public function render()
    {
        $stats = [
            'suppliers' => 48,
            'pendingOrders' => 12,
            'thisMonth' => 28,
            'totalValue' => 125000000,
        ];

        $recentActivities = [
            ['title' => 'PO-2024-045 approved', 'subtitle' => 'PT Supplier Utama', 'time' => '10 minutes ago', 'type' => 'approval'],
            ['title' => 'New supplier onboarded', 'subtitle' => 'Fresh Dairy Co.', 'time' => '45 minutes ago', 'type' => 'supplier'],
            ['title' => 'Goods received', 'subtitle' => 'PO-2024-042', 'time' => 'Today, 10:15', 'type' => 'receipt'],
        ];

        $quickLinks = [
            ['label' => 'Suppliers', 'href' => route('procurement.suppliers'), 'icon' => 'heroicon-o-building-office', 'description' => 'Manage vendors'],
            ['label' => 'Purchase Orders', 'href' => route('procurement.orders'), 'icon' => 'heroicon-o-document-text', 'description' => 'View all orders'],
            ['label' => 'Create Order', 'href' => route('procurement.orders.create'), 'icon' => 'heroicon-o-plus-circle', 'description' => 'New purchase'],
            ['label' => 'Goods Receipt', 'href' => route('procurement.receipts'), 'icon' => 'heroicon-o-inbox-arrow-down', 'description' => 'Receive goods'],
            ['label' => 'Returns', 'href' => route('procurement.returns'), 'icon' => 'heroicon-o-arrow-uturn-left', 'description' => 'Return items'],
        ];

        return view('livewire.procurement.portal', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'quickLinks' => $quickLinks,
        ])->layoutData([
            'pageTitle' => 'Procurement',
            'pageTagline' => 'Suppliers & Purchasing',
            'activeModule' => 'procurement',
            'navLinks' => ProcurementNavigation::links('overview'),
        ]);
    }
}
