<?php

namespace App\Livewire\Procurement\Orders;

use App\Support\ProcurementNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public array $selectedItems = [];
    public bool $selectPage = false;
    public array $pageItemIds = [];

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
        $this->resetSelection();
    }

    public function setSort(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            return;
        }

        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }

    public function toggleSelectPage(): void
    {
        $this->selectPage = ! $this->selectPage;
        $this->selectedItems = $this->selectPage ? array_map('strval', $this->pageItemIds) : [];
    }

    public function selectPage(): void
    {
        $this->selectedItems = array_map('strval', $this->pageItemIds);
        $this->selectPage = true;
    }

    public function selectAll(): void
    {
        $this->selectedItems = array_map('strval', $this->pageItemIds);
        $this->selectPage = true;
    }

    public function deselectAll(): void
    {
        $this->resetSelection();
    }

    public function deleteSelected(): void
    {
        if (empty($this->selectedItems)) {
            return;
        }

        $this->resetSelection();
        $this->dispatch('notify', message: 'Selected orders deleted');
    }

    protected function resetSelection(): void
    {
        $this->selectedItems = [];
        $this->selectPage = false;
    }

    public function render(): View
    {
        // Mock data for orders
        $orders = collect([
            (object) ['id' => 1, 'reference' => 'PO-2024-001', 'supplier_name' => 'PT Supplier Utama', 'items_count' => 15, 'total' => 25000000, 'status' => 'approved', 'created_at' => now()->subDays(1)],
            (object) ['id' => 2, 'reference' => 'PO-2024-002', 'supplier_name' => 'CV Mitra Jaya', 'items_count' => 8, 'total' => 12500000, 'status' => 'pending', 'created_at' => now()->subDays(2)],
            (object) ['id' => 3, 'reference' => 'PO-2024-003', 'supplier_name' => 'UD Berkah Makmur', 'items_count' => 22, 'total' => 45000000, 'status' => 'received', 'created_at' => now()->subDays(5)],
        ]);

        $this->pageItemIds = $orders->pluck('id')->toArray();

        $stats = [
            'totalOrders' => 156,
            'pending' => 12,
            'approved' => 8,
            'received' => 136,
        ];

        return view('livewire.procurement.orders.index', [
            'orders' => $orders,
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Purchase Orders',
            'pageTagline' => 'Procurement',
            'activeModule' => 'procurement',
            'navLinks' => ProcurementNavigation::links('orders', 'all'),
        ]);
    }
}
