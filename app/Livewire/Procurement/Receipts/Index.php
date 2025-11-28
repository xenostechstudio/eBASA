<?php

namespace App\Livewire\Procurement\Receipts;

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

    protected function resetSelection(): void
    {
        $this->selectedItems = [];
        $this->selectPage = false;
    }

    public function render(): View
    {
        // Mock data for receipts
        $receipts = collect([
            (object) ['id' => 1, 'reference' => 'GR-2024-001', 'po_reference' => 'PO-2024-001', 'supplier_name' => 'PT Supplier Utama', 'items_count' => 15, 'status' => 'complete', 'received_at' => now()->subDays(1)],
            (object) ['id' => 2, 'reference' => 'GR-2024-002', 'po_reference' => 'PO-2024-002', 'supplier_name' => 'CV Mitra Jaya', 'items_count' => 8, 'status' => 'partial', 'received_at' => now()->subDays(2)],
            (object) ['id' => 3, 'reference' => 'GR-2024-003', 'po_reference' => 'PO-2024-003', 'supplier_name' => 'UD Berkah Makmur', 'items_count' => 22, 'status' => 'complete', 'received_at' => now()->subDays(5)],
        ]);

        $this->pageItemIds = $receipts->pluck('id')->toArray();

        $stats = [
            'totalReceipts' => 234,
            'thisMonth' => 28,
            'complete' => 210,
            'partial' => 24,
        ];

        return view('livewire.procurement.receipts.index', [
            'receipts' => $receipts,
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Goods Receipt',
            'pageTagline' => 'Procurement',
            'activeModule' => 'procurement',
            'navLinks' => ProcurementNavigation::links('receipts'),
        ]);
    }
}
