<?php

namespace App\Livewire\Inventory\Catalog\Products;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\ProductCategory;
use App\Support\InventoryNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: 'all')]
    public string $categoryFilter = 'all';

    #[Url(except: 'all')]
    public string $statusFilter = 'all';

    #[Url(except: 'all')]
    public string $stockFilter = 'all';

    #[Url(except: 'name')]
    public string $sortField = 'name';

    #[Url(except: 'asc')]
    public string $sortDirection = 'asc';

    #[Url(except: '15')]
    public int $perPage = 15;

    public array $selectedItems = [];
    public bool $selectPage = false;
    public array $pageItemIds = [];

    protected $queryString = ['search', 'categoryFilter', 'statusFilter', 'stockFilter', 'sortField', 'sortDirection', 'perPage'];

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedCategoryFilter(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedStockFilter(): void
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

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
        $this->resetSelection();
    }

    public function setStockFilter(string $stock): void
    {
        $this->stockFilter = $stock;
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
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

    public function clearFilters(): void
    {
        $this->search = '';
        $this->categoryFilter = 'all';
        $this->statusFilter = 'all';
        $this->stockFilter = 'all';
        $this->resetPage();
        $this->resetSelection();
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
        $branchId = (int) session('active_branch_id', 0) ?: null;
        if (! $branchId) {
            return;
        }

        $allIds = $this->getFilteredQuery()->pluck('branch_products.id')->map(fn ($id) => (int) $id)->all();
        $this->selectedItems = array_map('strval', $allIds);
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

        BranchProduct::whereIn('id', array_map('intval', $this->selectedItems))->delete();
        $this->resetSelection();

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Products removed',
            'message' => 'Selected products removed from this branch.',
        ]);
    }

    public function export(string $format = 'csv'): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $branchId = (int) session('active_branch_id', 0) ?: null;
        $products = $this->getFilteredQuery()->with(['product.category'])->get();

        $filename = 'branch-products-' . now()->format('Y-m-d-His') . '.' . $format;

        return Response::streamDownload(function () use ($products) {
            $handle = fopen('php://output', 'w');

            // Header
            fputcsv($handle, ['SKU', 'Name', 'Category', 'Selling Price', 'Cost Price', 'Stock', 'Min Stock', 'Status', 'Featured']);

            foreach ($products as $bp) {
                fputcsv($handle, [
                    $bp->product->sku ?? '',
                    $bp->product->name ?? '',
                    $bp->product->category->name ?? '',
                    $bp->selling_price ?? $bp->product->selling_price ?? 0,
                    $bp->cost_price ?? $bp->product->cost_price ?? 0,
                    $bp->stock_quantity ?? 0,
                    $bp->min_stock_level ?? 0,
                    $bp->is_available ? 'Available' : 'Unavailable',
                    $bp->is_featured ? 'Yes' : 'No',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    protected function resetSelection(): void
    {
        $this->selectedItems = [];
        $this->selectPage = false;
    }

    public function goToProduct(int $branchProductId): void
    {
        $this->redirectRoute('inventory.catalog.products.edit', ['branchProduct' => $branchProductId], navigate: true);
    }

    protected function getFilteredQuery()
    {
        $branchId = (int) session('active_branch_id', 0) ?: null;

        $query = BranchProduct::query()
            ->with(['product.category', 'branch']);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($this->search !== '') {
            $query->whereHas('product', function ($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%')
                    ->orWhere('sku', 'ilike', '%' . $this->search . '%')
                    ->orWhere('barcode', 'ilike', '%' . $this->search . '%');
            });
        }

        if ($this->categoryFilter !== 'all') {
            $query->whereHas('product', function ($q) {
                $q->where('category_id', (int) $this->categoryFilter);
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('is_available', $this->statusFilter === 'available');
        }

        if ($this->stockFilter !== 'all') {
            switch ($this->stockFilter) {
                case 'low':
                case 'low_stock':
                    $query->where('stock_quantity', '>', 0)
                        ->whereColumn('stock_quantity', '<=', 'min_stock_level');
                    break;
                case 'out':
                case 'out_of_stock':
                    $query->where('stock_quantity', '<=', 0);
                    break;
                case 'in':
                case 'in_stock':
                    $query->where('stock_quantity', '>', 0)
                        ->whereColumn('stock_quantity', '>', 'min_stock_level');
                    break;
            }
        }

        // Sort handling
        if (in_array($this->sortField, ['name', 'sku', 'selling_price'])) {
            $query->join('products', 'branch_products.product_id', '=', 'products.id')
                ->select('branch_products.*')
                ->orderBy('products.' . $this->sortField, $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query;
    }

    public function render(): View
    {
        $branchId = (int) session('active_branch_id', 0) ?: null;
        $activeBranch = $branchId ? Branch::find($branchId) : null;

        $branchProducts = $this->getFilteredQuery()->paginate($this->perPage);

        $this->pageItemIds = $branchProducts->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->selectPage = ! empty($this->pageItemIds) && empty(array_diff($this->pageItemIds, array_map('intval', $this->selectedItems)));

        // Stats for current branch
        $statsQuery = BranchProduct::query();
        if ($branchId) {
            $statsQuery->where('branch_id', $branchId);
        }

        $stats = [
            'totalProducts' => (clone $statsQuery)->count(),
            'available' => (clone $statsQuery)->where('is_available', true)->count(),
            'unavailable' => (clone $statsQuery)->where('is_available', false)->count(),
            'lowStock' => (clone $statsQuery)->whereColumn('stock_quantity', '<=', 'min_stock_level')->where('stock_quantity', '>', 0)->count(),
            'outOfStock' => (clone $statsQuery)->where('stock_quantity', '<=', 0)->count(),
        ];

        $hasActiveFilters = $this->search !== '' || $this->categoryFilter !== 'all' || $this->statusFilter !== 'all' || $this->stockFilter !== 'all';

        return view('livewire.inventory.catalog.products.index', [
            'branchProducts' => $branchProducts,
            'categories' => ProductCategory::orderBy('name')->get(['id', 'name']),
            'stats' => $stats,
            'activeBranch' => $activeBranch,
            'hasActiveFilters' => $hasActiveFilters,
        ])->layoutData([
            'pageTitle' => 'Branch Products',
            'pageTagline' => 'Inventory · Catalog',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('catalog', 'products'),
        ]);
    }
}
