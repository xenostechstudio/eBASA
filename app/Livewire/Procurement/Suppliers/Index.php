<?php

namespace App\Livewire\Procurement\Suppliers;

use App\Support\ProcurementNavigation;
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
        $this->dispatch('notify', message: 'Selected suppliers deleted');
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
        // Mock data for suppliers
        $suppliers = collect([
            (object) ['id' => 1, 'name' => 'PT Supplier Utama', 'code' => 'SUP-001', 'contact_name' => 'Budi Santoso', 'phone' => '021-1234567', 'email' => 'budi@supplier.com', 'is_active' => true, 'orders_count' => 45],
            (object) ['id' => 2, 'name' => 'CV Mitra Jaya', 'code' => 'SUP-002', 'contact_name' => 'Siti Rahayu', 'phone' => '021-7654321', 'email' => 'siti@mitrajaya.com', 'is_active' => true, 'orders_count' => 32],
            (object) ['id' => 3, 'name' => 'UD Berkah Makmur', 'code' => 'SUP-003', 'contact_name' => 'Ahmad Wijaya', 'phone' => '024-5551234', 'email' => 'ahmad@berkah.com', 'is_active' => false, 'orders_count' => 18],
        ]);

        $this->pageItemIds = $suppliers->pluck('id')->toArray();

        $stats = [
            'totalSuppliers' => 48,
            'active' => 42,
            'inactive' => 6,
            'newThisMonth' => 3,
        ];

        return view('livewire.procurement.suppliers.index', [
            'suppliers' => $suppliers,
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Suppliers',
            'pageTagline' => 'Procurement',
            'activeModule' => 'procurement',
            'navLinks' => ProcurementNavigation::links('suppliers'),
        ]);
    }
}
