<?php

namespace App\Livewire\HumanResource\Payroll\PayrollItems;

use App\Models\PayrollItem;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $typeFilter = '';

    #[Url]
    public string $categoryFilter = '';

    public int $perPage = 15;
    public array $perPageOptions = [10, 15, 25, 50];

    public string $sortField = 'sort_order';
    public string $sortDirection = 'asc';

    public bool $showDeleteConfirm = false;
    public ?int $deletingItemId = null;
    public ?string $deletingItemName = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function setTypeFilter(string $value): void
    {
        $this->typeFilter = $value;
        $this->resetPage();
    }

    public function setCategoryFilter(string $value): void
    {
        $this->categoryFilter = $value;
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete(int $id): void
    {
        $item = PayrollItem::find($id);
        if ($item) {
            $this->deletingItemId = $id;
            $this->deletingItemName = $item->name;
            $this->showDeleteConfirm = true;
        }
    }

    public function deleteItem(): void
    {
        if ($this->deletingItemId) {
            PayrollItem::destroy($this->deletingItemId);
            session()->flash('flash', [
                'type' => 'success',
                'message' => 'Payroll item deleted successfully.',
            ]);
        }
        $this->cancelDelete();
    }

    public function cancelDelete(): void
    {
        $this->showDeleteConfirm = false;
        $this->deletingItemId = null;
        $this->deletingItemName = null;
    }

    public function export(string $format): void
    {
        // Placeholder for export functionality
        session()->flash('flash', [
            'type' => 'info',
            'message' => "Export to {$format} will be implemented.",
        ]);
    }

    public function render(): View
    {
        $items = PayrollItem::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->typeFilter, fn ($q) => $q->where('type', $this->typeFilter))
            ->when($this->categoryFilter, fn ($q) => $q->where('category', $this->categoryFilter))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $stats = [
            'total' => PayrollItem::count(),
            'earnings' => PayrollItem::where('type', 'earning')->count(),
            'deductions' => PayrollItem::where('type', 'deduction')->count(),
        ];

        return view('livewire.hr.payroll.payroll-items.index', [
            'items' => $items,
            'stats' => $stats,
            'categories' => PayrollItem::CATEGORIES,
        ])->layoutData([
            'pageTitle' => 'Payroll Items',
            'pageTagline' => 'HR · Payroll',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('payroll', 'payroll-items'),
        ]);
    }
}
