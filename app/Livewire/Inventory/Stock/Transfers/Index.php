<?php

namespace App\Livewire\Inventory\Stock\Transfers;

use App\Models\Warehouse;
use App\Support\InventoryNavigation;
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

    public bool $showCreateModal = false;

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
        $this->dispatch('notify', message: 'Selected transfers deleted');
    }

    protected function resetSelection(): void
    {
        $this->selectedItems = [];
        $this->selectPage = false;
    }

    public function openCreateModal(): void
    {
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function render(): View
    {
        // Mock data for transfers
        $transfers = collect([
            (object) ['id' => 1, 'reference' => 'TRF-001', 'from_warehouse' => 'Main Warehouse', 'to_warehouse' => 'Branch Pemalang', 'items_count' => 5, 'status' => 'completed', 'created_at' => now()->subDays(1)],
            (object) ['id' => 2, 'reference' => 'TRF-002', 'from_warehouse' => 'Main Warehouse', 'to_warehouse' => 'Branch Tegal', 'items_count' => 12, 'status' => 'in_transit', 'created_at' => now()->subDays(2)],
            (object) ['id' => 3, 'reference' => 'TRF-003', 'from_warehouse' => 'Branch Banjaran', 'to_warehouse' => 'Main Warehouse', 'items_count' => 3, 'status' => 'pending', 'created_at' => now()->subDays(3)],
        ]);

        $this->pageItemIds = $transfers->pluck('id')->toArray();

        $stats = [
            'totalTransfers' => 234,
            'pending' => 12,
            'inTransit' => 8,
            'completed' => 214,
        ];

        return view('livewire.inventory.stock.transfers.index', [
            'transfers' => $transfers,
            'warehouses' => Warehouse::orderBy('name')->get(['id', 'name']),
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Stock Transfers',
            'pageTagline' => 'Inventory · Stock',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('stock', 'transfers'),
        ]);
    }
}
