<?php

namespace App\Livewire\Inventory\Catalog\PriceLists;

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
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

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
        $this->dispatch('notify', message: 'Selected price lists deleted');
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

    public function render(): View
    {
        // Mock data for price lists
        $priceLists = collect([
            (object) ['id' => 1, 'name' => 'Retail Price', 'code' => 'PL-RETAIL', 'products_count' => 150, 'is_default' => true, 'is_active' => true, 'valid_from' => now()->subMonths(6), 'valid_until' => null],
            (object) ['id' => 2, 'name' => 'Wholesale Price', 'code' => 'PL-WHOLESALE', 'products_count' => 120, 'is_default' => false, 'is_active' => true, 'valid_from' => now()->subMonths(3), 'valid_until' => now()->addMonths(9)],
            (object) ['id' => 3, 'name' => 'Promo December', 'code' => 'PL-PROMO-DEC', 'products_count' => 45, 'is_default' => false, 'is_active' => false, 'valid_from' => now()->startOfMonth(), 'valid_until' => now()->endOfMonth()],
        ]);

        $this->pageItemIds = $priceLists->pluck('id')->toArray();

        $stats = [
            'totalLists' => 12,
            'active' => 8,
            'inactive' => 4,
            'default' => 1,
        ];

        return view('livewire.inventory.catalog.price-lists.index', [
            'priceLists' => $priceLists,
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Price Lists',
            'pageTagline' => 'Inventory · Catalog',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('catalog', 'price-lists'),
        ]);
    }
}
