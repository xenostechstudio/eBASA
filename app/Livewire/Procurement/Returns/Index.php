<?php

namespace App\Livewire\Procurement\Returns;

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
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

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
        $this->dispatch('notify', message: 'Selected returns deleted');
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
        // Mock data for returns
        $returns = collect([
            (object) ['id' => 1, 'reference' => 'RET-2024-001', 'supplier_name' => 'PT Supplier Utama', 'items_count' => 3, 'reason' => 'Damaged goods', 'status' => 'approved', 'created_at' => now()->subDays(2)],
            (object) ['id' => 2, 'reference' => 'RET-2024-002', 'supplier_name' => 'CV Mitra Jaya', 'items_count' => 1, 'reason' => 'Wrong item', 'status' => 'pending', 'created_at' => now()->subDays(3)],
            (object) ['id' => 3, 'reference' => 'RET-2024-003', 'supplier_name' => 'UD Berkah Makmur', 'items_count' => 5, 'reason' => 'Quality issue', 'status' => 'completed', 'created_at' => now()->subDays(7)],
        ]);

        $this->pageItemIds = $returns->pluck('id')->toArray();

        $stats = [
            'totalReturns' => 45,
            'pending' => 5,
            'approved' => 8,
            'completed' => 32,
        ];

        return view('livewire.procurement.returns.index', [
            'returns' => $returns,
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Returns',
            'pageTagline' => 'Procurement',
            'activeModule' => 'procurement',
            'navLinks' => ProcurementNavigation::links('returns'),
        ]);
    }
}
