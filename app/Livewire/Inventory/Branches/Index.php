<?php

namespace App\Livewire\Inventory\Branches;

use App\Models\Branch;
use App\Support\InventoryNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @method $this layoutData(array $data)
 */
#[Layout('layouts.portal')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public int $perPage = 10;
    public array $perPageOptions = [10, 25, 50];

    protected array $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
    ];

    public function updated($property): void
    {
        if (in_array($property, ['search', 'statusFilter', 'perPage'], true)) {
            $this->resetPage();
        }
    }

    public function setStatusFilter(string $filter): void
    {
        $this->statusFilter = $filter;
        $this->resetPage();
    }

    public function updatedPerPage($value): void
    {
        $value = (int) $value;
        $this->perPage = in_array($value, $this->perPageOptions, true) ? $value : 10;
        $this->resetPage();
    }

    protected function query(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Branch::query();

        if ($this->search !== '') {
            $query->where(function ($builder) {
                $builder->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('code', 'like', '%'.$this->search.'%')
                    ->orWhere('city', 'like', '%'.$this->search.'%')
                    ->orWhere('province', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        return $query->orderBy('name');
    }

    public function render(): View
    {
        $branches = $this->query()->paginate($this->perPage);
        $statsBase = Branch::query();

        $stats = [
            'total' => (clone $statsBase)->count(),
            'active' => (clone $statsBase)->where('is_active', true)->count(),
            'inactive' => (clone $statsBase)->where('is_active', false)->count(),
        ];

        /** @noinspection PhpUndefinedMethodInspection */
        return view('livewire.inventory.branches.index', [
            'branches' => $branches,
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Inventory · Branches',
            'showBrand' => false,
            'navLinks' => InventoryNavigation::links('branches', 'branches.index'),
        ]);
    }
}
