<?php

namespace App\Livewire\HumanResource\Positions;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Position;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $branchFilter = 'all';
    public string $departmentFilter = 'all';
    public string $sortField = 'title';
    public string $sortDirection = 'asc';

    /** @var array<string, bool> */
    public array $columnVisibility = [
        'position' => true,
        'department' => true,
        'branch' => true,
        'level' => true,
        'job_family' => true,
        'people_manager' => true,
        'employees' => true,
    ];

    public array $selectedPositions = [];
    public bool $selectPage = false;
    public array $pagePositionIds = [];

    protected string $columnVisibilitySessionKey = 'hr.positions.columns';

    public function mount(): void
    {
        $stored = session($this->columnVisibilitySessionKey);

        if (is_array($stored)) {
            foreach ($this->columnVisibility as $column => $visible) {
                if (array_key_exists($column, $stored)) {
                    $this->columnVisibility[$column] = (bool) $stored[$column];
                }
            }
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function setBranchFilter(string $branchId): void
    {
        $this->branchFilter = $branchId;
        $this->resetPage();
    }

    public function setDepartmentFilter(string $departmentId): void
    {
        $this->departmentFilter = $departmentId;
        $this->resetPage();
    }

    public function resetColumns(): void
    {
        $this->columnVisibility = [
            'position' => true,
            'department' => true,
            'branch' => true,
            'level' => true,
            'job_family' => true,
            'people_manager' => true,
            'employees' => true,
        ];

        session()->forget($this->columnVisibilitySessionKey);
        session([$this->columnVisibilitySessionKey => $this->columnVisibility]);
    }

    public function updatedColumnVisibility($value, string $key): void
    {
        if (array_key_exists($key, $this->columnVisibility)) {
            $this->columnVisibility[$key] = (bool) $value;
            session([$this->columnVisibilitySessionKey => $this->columnVisibility]);
        }
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

        if ($this->selectPage) {
            $this->selectedPositions = array_map('strval', $this->pagePositionIds);
            return;
        }

        $this->selectedPositions = [];
    }

    public function updatedSelectedPositions(): void
    {
        $normalized = array_values(array_unique(array_map('intval', $this->selectedPositions)));
        $this->selectedPositions = array_map('strval', $normalized);
        $this->selectPage = $this->pageHasAllSelected($normalized);
    }

    protected function pageHasAllSelected(?array $selectedIds = null): bool
    {
        $selectedIds ??= array_values(array_unique(array_map('intval', $this->selectedPositions)));

        if (empty($this->pagePositionIds)) {
            return false;
        }

        return empty(array_diff($this->pagePositionIds, $selectedIds));
    }

    public function render(): View
    {
        $positionsQuery = Position::query()
            ->with(['department', 'branch'])
            ->withCount('employees');

        if ($this->search !== '') {
            $positionsQuery->where(function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('code', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->branchFilter !== 'all') {
            $positionsQuery->where('branch_id', (int) $this->branchFilter);
        }

        if ($this->departmentFilter !== 'all') {
            $positionsQuery->where('department_id', (int) $this->departmentFilter);
        }

        $allowedSorts = ['title', 'code', 'level', 'job_family', 'employees_count'];
        $sortField = in_array($this->sortField, $allowedSorts, true) ? $this->sortField : 'title';

        $positions = $positionsQuery
            ->orderBy($sortField, $this->sortDirection)
            ->paginate(10);

        $this->pagePositionIds = $positions->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->selectPage = $this->pageHasAllSelected();

        $stats = [
            'total' => Position::count(),
            'peopleManagers' => Position::where('is_people_manager', true)->count(),
            'individualContributors' => Position::where('is_people_manager', false)->count(),
        ];

        return view('livewire.hr.positions.index', [
            'positions' => $positions,
            'branches' => Branch::orderBy('name')->get(['id', 'name']),
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'stats' => $stats,
            'columnVisibility' => $this->columnVisibility,
            'selectedPositions' => $this->selectedPositions,
            'selectPage' => $this->selectPage,
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
            'branchFilter' => $this->branchFilter,
            'departmentFilter' => $this->departmentFilter,
        ])->layoutData([
            'pageTitle' => 'Human Resource',
            'showBrand' => false,
            'navLinks' => HumanResourceNavigation::links('people', 'positions'),
        ]);
    }
}
