<?php

namespace App\Livewire\Procurement\Suppliers;

use App\Models\Supplier;
use App\Support\ProcurementNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
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

        Supplier::whereIn('id', $this->selectedItems)->delete();

        $this->resetSelection();
        $this->dispatch('notify', message: 'Selected suppliers deleted');
    }

    protected function resetSelection(): void
    {
        $this->selectedItems = [];
        $this->selectPage = false;
    }

    public function goToSupplier(int $supplierId): void
    {
        $this->redirectRoute('procurement.suppliers.edit', ['supplier' => $supplierId], navigate: true);
    }

    #[Computed]
    public function suppliers()
    {
        return Supplier::query()
            ->withCount('purchaseOrders')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('code', 'like', "%{$this->search}%")
                    ->orWhere('contact_name', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter === 'active', fn ($q) => $q->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);
    }

    #[Computed]
    public function stats(): array
    {
        return [
            'totalSuppliers' => Supplier::count(),
            'active' => Supplier::where('is_active', true)->count(),
            'inactive' => Supplier::where('is_active', false)->count(),
            'newThisMonth' => Supplier::whereMonth('created_at', now()->month)->count(),
        ];
    }

    public function render(): View
    {
        $this->pageItemIds = $this->suppliers->pluck('id')->toArray();

        return view('livewire.procurement.suppliers.index')->layoutData([
            'pageTitle' => 'Suppliers',
            'pageTagline' => 'Procurement',
            'activeModule' => 'procurement',
            'navLinks' => ProcurementNavigation::links('suppliers'),
        ]);
    }
}
