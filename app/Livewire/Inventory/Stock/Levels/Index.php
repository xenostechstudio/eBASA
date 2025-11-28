<?php

namespace App\Livewire\Inventory\Stock\Levels;

use App\Models\Branch;
use App\Models\Product;
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
    public string $categoryFilter = 'all';
    public string $stockFilter = 'all';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

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

    public function setCategoryFilter(string $categoryId): void
    {
        $this->categoryFilter = $categoryId;
        $this->resetPage();
        $this->resetSelection();
    }

    public function setStockFilter(string $status): void
    {
        $this->stockFilter = $status;
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

        if ($this->selectPage) {
            $this->selectedItems = array_map('strval', $this->pageItemIds);
            return;
        }

        $this->selectedItems = [];
    }

    public function selectPage(): void
    {
        $this->selectedItems = array_map('strval', $this->pageItemIds);
        $this->selectPage = $this->pageHasAllSelected();
    }

    public function selectAll(): void
    {
        $allIds = Product::query()->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->selectedItems = array_map('strval', $allIds);
        $this->selectPage = $this->pageHasAllSelected();
    }

    public function deselectAll(): void
    {
        $this->resetSelection();
    }

    public function deleteSelected(): void
    {
        // Stock levels are not directly deletable
        $this->dispatch('notify', message: 'Stock levels cannot be deleted directly');
    }

    public function updatedSelectedItems(): void
    {
        $normalized = array_values(array_unique(array_map('intval', $this->selectedItems)));
        $this->selectedItems = array_map('strval', $normalized);
        $this->selectPage = $this->pageHasAllSelected($normalized);
    }

    protected function resetSelection(): void
    {
        $this->selectedItems = [];
        $this->selectPage = false;
    }

    protected function pageHasAllSelected(?array $selectedIds = null): bool
    {
        $selectedIds ??= array_values(array_unique(array_map('intval', $this->selectedItems)));

        if (empty($this->pageItemIds)) {
            return false;
        }

        return empty(array_diff($this->pageItemIds, $selectedIds));
    }

    public function render(): View
    {
        $activeBranchId = (int) session('active_branch_id', 0) ?: null;

        $productsQuery = Product::query()
            ->with(['category']);

        if ($this->search !== '') {
            $productsQuery->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('sku', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->categoryFilter !== 'all') {
            $productsQuery->where('category_id', (int) $this->categoryFilter);
        }

        $allowedSorts = ['name', 'sku', 'stock_quantity', 'reorder_level'];
        $sortField = in_array($this->sortField, $allowedSorts, true) ? $this->sortField : 'name';

        $products = $productsQuery
            ->orderBy($sortField, $this->sortDirection)
            ->paginate(10);

        $this->pageItemIds = $products->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->selectPage = $this->pageHasAllSelected();

        $stats = [
            'totalProducts' => Product::count(),
            'inStock' => Product::where('stock_quantity', '>', 0)->count(),
            'lowStock' => Product::whereColumn('stock_quantity', '<=', 'reorder_level')
                ->where('stock_quantity', '>', 0)->count(),
            'outOfStock' => Product::where('stock_quantity', '<=', 0)->count(),
        ];

        return view('livewire.inventory.stock.levels.index', [
            'products' => $products,
            'warehouses' => Warehouse::orderBy('name')->get(['id', 'name']),
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Stock Levels',
            'pageTagline' => 'Inventory · Stock',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('stock', 'levels'),
        ]);
    }
}
