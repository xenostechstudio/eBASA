<?php

namespace App\Livewire\Inventory\Catalog\Bundles;

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
        $this->dispatch('notify', message: 'Selected bundles deleted');
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
        // Mock data for bundles
        $bundles = collect([
            (object) ['id' => 1, 'name' => 'Starter Pack', 'sku' => 'BDL-001', 'items_count' => 5, 'price' => 150000, 'is_active' => true],
            (object) ['id' => 2, 'name' => 'Premium Bundle', 'sku' => 'BDL-002', 'items_count' => 10, 'price' => 350000, 'is_active' => true],
            (object) ['id' => 3, 'name' => 'Family Pack', 'sku' => 'BDL-003', 'items_count' => 8, 'price' => 250000, 'is_active' => false],
        ]);

        $this->pageItemIds = $bundles->pluck('id')->toArray();

        $stats = [
            'totalBundles' => 24,
            'active' => 18,
            'inactive' => 6,
            'avgItems' => 6,
        ];

        return view('livewire.inventory.catalog.bundles.index', [
            'bundles' => $bundles,
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Bundles',
            'pageTagline' => 'Inventory · Catalog',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('catalog', 'bundles'),
        ]);
    }
}
