<?php

namespace App\Livewire\Inventory\Catalog\Products;

use App\Models\Product;
use App\Models\ProductCategory;
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
    public string $statusFilter = 'all';
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
        $allIds = Product::query()->pluck('id')->map(fn ($id) => (int) $id)->all();
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

        Product::query()->whereIn('id', array_map('intval', $this->selectedItems))->delete();
        $this->resetSelection();
        $this->dispatch('notify', message: 'Selected products deleted');
    }

    protected function resetSelection(): void
    {
        $this->selectedItems = [];
        $this->selectPage = false;
    }

    public function render(): View
    {
        $productsQuery = Product::query()->with(['category']);

        if ($this->search !== '') {
            $productsQuery->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('sku', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->categoryFilter !== 'all') {
            $productsQuery->where('category_id', (int) $this->categoryFilter);
        }

        if ($this->statusFilter !== 'all') {
            $productsQuery->where('is_active', $this->statusFilter === 'active');
        }

        $allowedSorts = ['name', 'sku', 'price', 'stock_quantity'];
        $sortField = in_array($this->sortField, $allowedSorts, true) ? $this->sortField : 'name';

        $products = $productsQuery
            ->orderBy($sortField, $this->sortDirection)
            ->paginate(10);

        $this->pageItemIds = $products->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->selectPage = ! empty($this->pageItemIds) && empty(array_diff($this->pageItemIds, array_map('intval', $this->selectedItems)));

        $stats = [
            'totalProducts' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
            'categories' => ProductCategory::count(),
        ];

        return view('livewire.inventory.catalog.products.index', [
            'products' => $products,
            'categories' => ProductCategory::orderBy('name')->get(['id', 'name']),
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Products',
            'pageTagline' => 'Inventory · Catalog',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('catalog', 'products'),
        ]);
    }
}
