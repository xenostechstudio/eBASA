<?php

namespace App\Livewire\Inventory\Stock\Adjustments;

use App\Models\StockAdjustment;
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

    public function goToAdjustment(int $adjustmentId): void
    {
        $this->redirectRoute('inventory.stock.adjustments.show', ['adjustment' => $adjustmentId], navigate: true);
    }

    public function render(): View
    {
        $query = StockAdjustment::query()
            ->with(['warehouse', 'items.product']);

        if ($this->search !== '') {
            $search = $this->search;

            $query->where(function ($q) use ($search) {
                $q->where('reference', 'ilike', "%{$search}%")
                    ->orWhere('reason', 'ilike', "%{$search}%");
            });
        }

        if ($this->warehouseFilter !== 'all') {
            $query->where('warehouse_id', $this->warehouseFilter);
        }

        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        $adjustments = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        $this->pageItemIds = $adjustments->pluck('id')->toArray();

        $stats = [
            'totalAdjustments' => StockAdjustment::count(),
            'additions' => StockAdjustment::where('type', 'addition')->count(),
            'reductions' => StockAdjustment::where('type', 'reduction')->count(),
            'thisMonth' => StockAdjustment::whereBetween('adjustment_date', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])->count(),
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
