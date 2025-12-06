<?php

namespace App\Livewire\Inventory\Catalog\Bundles;

use App\Models\Branch;
use App\Models\Bundle;
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
    public string $statusFilter = 'all';

    #[Url(except: 'all')]
    public string $validityFilter = 'all';

    #[Url(except: 'name')]
    public string $sortField = 'name';

    #[Url(except: 'asc')]
    public string $sortDirection = 'asc';

    #[Url(except: '15')]
    public int $perPage = 15;

    public array $selectedItems = [];
    public bool $selectPage = false;
    public array $pageItemIds = [];

    protected $queryString = ['search', 'statusFilter', 'validityFilter', 'sortField', 'sortDirection', 'perPage'];

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedValidityFilter(): void
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

    public function setValidityFilter(string $validity): void
    {
        $this->validityFilter = $validity;
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
        $this->statusFilter = 'all';
        $this->validityFilter = 'all';
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
        $allIds = $this->getFilteredQuery()->pluck('id')->map(fn ($id) => (int) $id)->all();
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

        Bundle::whereIn('id', array_map('intval', $this->selectedItems))->delete();
        $this->resetSelection();

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Bundles deleted',
            'message' => 'Selected bundles have been deleted.',
        ]);
    }

    public function export(string $format = 'csv'): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $bundles = $this->getFilteredQuery()->withCount('items')->with('items.product')->get();

        $filename = 'bundles-' . now()->format('Y-m-d-His') . '.' . $format;

        return Response::streamDownload(function () use ($bundles) {
            $handle = fopen('php://output', 'w');

            // Header
            fputcsv($handle, ['SKU', 'Name', 'Description', 'Bundle Price', 'Items Count', 'Valid From', 'Valid Until', 'Status']);

            foreach ($bundles as $bundle) {
                fputcsv($handle, [
                    $bundle->sku ?? '',
                    $bundle->name ?? '',
                    $bundle->description ?? '',
                    $bundle->bundle_price ?? 0,
                    $bundle->items_count ?? 0,
                    $bundle->valid_from?->format('Y-m-d') ?? '',
                    $bundle->valid_until?->format('Y-m-d') ?? '',
                    $bundle->is_active ? 'Active' : 'Inactive',
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

    public function goToBundle(int $bundleId): void
    {
        $this->redirectRoute('inventory.catalog.bundles.edit', ['bundle' => $bundleId], navigate: true);
    }

    protected function getFilteredQuery()
    {
        $branchId = (int) session('active_branch_id', 0) ?: null;

        $query = Bundle::query()
            ->withCount('items')
            ->forBranch($branchId);

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%')
                    ->orWhere('sku', 'ilike', '%' . $this->search . '%')
                    ->orWhere('description', 'ilike', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        if ($this->validityFilter !== 'all') {
            $now = now();
            if ($this->validityFilter === 'valid') {
                $query->where(function ($q) use ($now) {
                    $q->where(function ($q2) use ($now) {
                        $q2->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
                    })->where(function ($q2) use ($now) {
                        $q2->whereNull('valid_until')->orWhere('valid_until', '>=', $now);
                    });
                });
            } elseif ($this->validityFilter === 'expired') {
                $query->whereNotNull('valid_until')->where('valid_until', '<', $now);
            } elseif ($this->validityFilter === 'upcoming') {
                $query->whereNotNull('valid_from')->where('valid_from', '>', $now);
            }
        }

        return $query->orderBy($this->sortField, $this->sortDirection);
    }

    public function render(): View
    {
        $branchId = (int) session('active_branch_id', 0) ?: null;
        $activeBranch = $branchId ? Branch::find($branchId) : null;

        $bundles = $this->getFilteredQuery()->paginate($this->perPage);

        $this->pageItemIds = $bundles->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->selectPage = ! empty($this->pageItemIds) && empty(array_diff($this->pageItemIds, array_map('intval', $this->selectedItems)));

        $statsQuery = Bundle::forBranch($branchId);
        $now = now();

        $stats = [
            'totalBundles' => (clone $statsQuery)->count(),
            'active' => (clone $statsQuery)->where('is_active', true)->count(),
            'inactive' => (clone $statsQuery)->where('is_active', false)->count(),
            'valid' => (clone $statsQuery)->where('is_active', true)
                ->where(function ($q) use ($now) {
                    $q->where(function ($q2) use ($now) {
                        $q2->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
                    })->where(function ($q2) use ($now) {
                        $q2->whereNull('valid_until')->orWhere('valid_until', '>=', $now);
                    });
                })->count(),
        ];

        $hasActiveFilters = $this->search !== '' || $this->statusFilter !== 'all' || $this->validityFilter !== 'all';

        return view('livewire.inventory.catalog.bundles.index', [
            'bundles' => $bundles,
            'stats' => $stats,
            'activeBranch' => $activeBranch,
            'hasActiveFilters' => $hasActiveFilters,
        ])->layoutData([
            'pageTitle' => 'Bundles',
            'pageTagline' => 'Inventory · Catalog',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('catalog', 'bundles'),
        ]);
    }
}
