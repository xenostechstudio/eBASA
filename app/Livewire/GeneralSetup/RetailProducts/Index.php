<?php

namespace App\Livewire\GeneralSetup\RetailProducts;

use App\Models\RetailProduct;
use App\Models\RetailProductCategory;
use App\Support\GeneralSetupNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
#[Title('Retail Products')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $categoryFilter = '';

    #[Url]
    public string $statusFilter = '';

    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    public bool $showDeleteModal = false;
    public ?int $productToDelete = null;

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->productToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteProduct(): void
    {
        if ($this->productToDelete) {
            RetailProduct::find($this->productToDelete)?->delete();
            $this->dispatch('notify', message: 'Product deleted successfully');
        }

        $this->showDeleteModal = false;
        $this->productToDelete = null;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->productToDelete = null;
    }

    #[Computed]
    public function products(): LengthAwarePaginator
    {
        return RetailProduct::query()
            ->with('category')
            ->when($this->search, fn ($q) => $q->where(function ($query) {
                $query->where('name', 'ilike', "%{$this->search}%")
                    ->orWhere('sku', 'ilike', "%{$this->search}%")
                    ->orWhere('barcode', 'ilike', "%{$this->search}%");
            }))
            ->when($this->categoryFilter, fn ($q) => $q->where('category_id', $this->categoryFilter))
            ->when($this->statusFilter !== '', fn ($q) => $q->where('is_active', $this->statusFilter === 'active'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);
    }

    #[Computed]
    public function categories(): \Illuminate\Database\Eloquent\Collection
    {
        return RetailProductCategory::orderBy('sort_order')->get();
    }

    #[Computed]
    public function stats(): array
    {
        return [
            'total' => RetailProduct::count(),
            'active' => RetailProduct::where('is_active', true)->count(),
            'low_stock' => RetailProduct::where('track_inventory', true)
                ->whereColumn('stock_quantity', '<=', 'min_stock_level')
                ->count(),
        ];
    }

    public function render(): View
    {
        return view('livewire.general-setup.retail-products.index')
            ->layoutData([
                'pageTitle' => 'Retail Products',
                'pageTagline' => 'General Setup',
                'activeModule' => 'general-setup',
                'navLinks' => GeneralSetupNavigation::links('master', 'retail-products'),
            ]);
    }
}
