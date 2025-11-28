<?php

namespace App\Livewire\Inventory\Stock\Adjustments;

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
    public string $warehouseFilter = 'all';
    public string $typeFilter = 'all';
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

    public function setWarehouseFilter(string $warehouseId): void
    {
        $this->warehouseFilter = $warehouseId;
        $this->resetPage();
        $this->resetSelection();
    }

    public function setTypeFilter(string $type): void
    {
        $this->typeFilter = $type;
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
        // Select all adjustments matching current filters
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

        // In real implementation, delete adjustments
        $this->resetSelection();
        $this->dispatch('notify', message: 'Selected adjustments deleted');
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
        // Mock data for adjustments
        $adjustments = collect([
            (object) ['id' => 1, 'reference' => 'ADJ-001', 'product_name' => 'Product A', 'warehouse' => 'Main Warehouse', 'type' => 'addition', 'quantity' => 50, 'reason' => 'Stock count correction', 'created_at' => now()->subDays(1)],
            (object) ['id' => 2, 'reference' => 'ADJ-002', 'product_name' => 'Product B', 'warehouse' => 'Branch Warehouse', 'type' => 'reduction', 'quantity' => 10, 'reason' => 'Damaged goods', 'created_at' => now()->subDays(2)],
            (object) ['id' => 3, 'reference' => 'ADJ-003', 'product_name' => 'Product C', 'warehouse' => 'Main Warehouse', 'type' => 'addition', 'quantity' => 100, 'reason' => 'Initial stock', 'created_at' => now()->subDays(3)],
        ]);

        $this->pageItemIds = $adjustments->pluck('id')->toArray();

        $stats = [
            'totalAdjustments' => 156,
            'additions' => 89,
            'reductions' => 67,
            'thisMonth' => 23,
        ];

        return view('livewire.inventory.stock.adjustments.index', [
            'adjustments' => $adjustments,
            'warehouses' => Warehouse::orderBy('name')->get(['id', 'name']),
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Stock Adjustments',
            'pageTagline' => 'Inventory · Stock',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('stock', 'adjustments'),
        ]);
    }
}
