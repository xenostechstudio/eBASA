<?php

namespace App\Livewire\Inventory\Stock\Levels;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Product;
use App\Models\ProductCategory;
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

    public function goToProduct(int $branchProductId): void
    {
        // Redirect to inventory catalog products (opens edit modal via URL param)
        $this->redirectRoute('inventory.catalog.products', ['edit' => $branchProductId], navigate: true);
    }

    public function goToAdjustment(): void
    {
        $this->redirectRoute('inventory.stock.adjustments.create', navigate: true);
    }

    public function render(): View
    {
        $branchId = (int) session('active_branch_id', 0) ?: null;
        $activeBranch = $branchId ? Branch::find($branchId) : null;

        // Query branch products with their master product data
        $query = BranchProduct::query()
            ->with(['product.category', 'branch'])
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId));

        if ($this->search !== '') {
            $query->whereHas('product', function ($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%')
                    ->orWhere('sku', 'ilike', '%' . $this->search . '%');
            });
        }

        if ($this->categoryFilter !== 'all') {
            $query->whereHas('product', function ($q) {
                $q->where('category_id', (int) $this->categoryFilter);
            });
        }

        if ($this->stockFilter !== 'all') {
            switch ($this->stockFilter) {
                case 'in_stock':
                    $query->where('stock_quantity', '>', 0)
                        ->whereColumn('stock_quantity', '>', 'min_stock_level');
                    break;
                case 'low_stock':
                    $query->where('stock_quantity', '>', 0)
                        ->whereColumn('stock_quantity', '<=', 'min_stock_level');
                    break;
                case 'out_of_stock':
                    $query->where('stock_quantity', '<=', 0);
                    break;
            }
        }

        // Handle sorting
        if (in_array($this->sortField, ['stock_quantity', 'min_stock_level'])) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            // Sort by product fields
            $query->orderBy(
                Product::select($this->sortField)
                    ->whereColumn('products.id', 'branch_products.product_id')
                    ->limit(1),
                $this->sortDirection
            );
        }

        $branchProducts = $query->paginate(15);

        $this->pageItemIds = $branchProducts->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->selectPage = $this->pageHasAllSelected();

        // Stats for branch products
        $statsQuery = BranchProduct::query()
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId));

        $stats = [
            'totalProducts' => (clone $statsQuery)->count(),
            'inStock' => (clone $statsQuery)->where('stock_quantity', '>', 0)
                ->whereColumn('stock_quantity', '>', 'min_stock_level')->count(),
            'lowStock' => (clone $statsQuery)->where('stock_quantity', '>', 0)
                ->whereColumn('stock_quantity', '<=', 'min_stock_level')->count(),
            'outOfStock' => (clone $statsQuery)->where('stock_quantity', '<=', 0)->count(),
        ];

        return view('livewire.inventory.stock.levels.index', [
            'branchProducts' => $branchProducts,
            'categories' => ProductCategory::orderBy('name')->get(['id', 'name']),
            'stats' => $stats,
            'activeBranch' => $activeBranch,
        ])->layoutData([
            'pageTitle' => 'Stock Levels',
            'pageTagline' => 'Inventory · Stock',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('stock', 'levels'),
        ]);
    }
}
