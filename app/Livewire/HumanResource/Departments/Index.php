<?php

namespace App\Livewire\HumanResource\Departments;

use App\Models\Branch;
use App\Models\Department;
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
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    /** @var array<string, bool> */
    public array $columnVisibility = [
        'department' => true,
        'branch' => true,
        'parent' => true,
        'lead' => true,
        'positions' => true,
        'employees' => true,
    ];

    public array $selectedDepartments = [];
    public bool $selectPage = false;
    public array $pageDepartmentIds = [];

    protected string $columnVisibilitySessionKey = 'hr.departments.columns';

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

    public function resetColumns(): void
    {
        $this->columnVisibility = [
            'department' => true,
            'branch' => true,
            'parent' => true,
            'lead' => true,
            'positions' => true,
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
            $this->selectedDepartments = array_map('strval', $this->pageDepartmentIds);
            return;
        }

        $this->selectedDepartments = [];
    }

    public function updatedSelectedDepartments(): void
    {
        $normalized = array_values(array_unique(array_map('intval', $this->selectedDepartments)));
        $this->selectedDepartments = array_map('strval', $normalized);
        $this->selectPage = $this->pageHasAllSelected($normalized);
    }

    protected function pageHasAllSelected(?array $selectedIds = null): bool
    {
        $selectedIds ??= array_values(array_unique(array_map('intval', $this->selectedDepartments)));

        if (empty($this->pageDepartmentIds)) {
            return false;
        }

        return empty(array_diff($this->pageDepartmentIds, $selectedIds));
    }

    public function render(): View
    {
        $departmentsQuery = Department::query()
            ->with(['branch', 'parent'])
            ->withCount(['positions', 'employees']);

        if ($this->search !== '') {
            $departmentsQuery->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('code', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->branchFilter !== 'all') {
            $departmentsQuery->where('branch_id', (int) $this->branchFilter);
        }

        $allowedSorts = ['name', 'code', 'positions_count', 'employees_count'];
        $sortField = in_array($this->sortField, $allowedSorts, true) ? $this->sortField : 'name';

        $departments = $departmentsQuery
            ->orderBy($sortField, $this->sortDirection)
            ->paginate(10);

        $this->pageDepartmentIds = $departments->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->selectPage = $this->pageHasAllSelected();

        $stats = [
            'total' => Department::count(),
            'withLead' => Department::whereNotNull('lead_name')->count(),
            'withoutLead' => Department::whereNull('lead_name')->count(),
        ];

        return view('livewire.hr.departments.index', [
            'departments' => $departments,
            'branches' => Branch::orderBy('name')->get(['id', 'name']),
            'stats' => $stats,
            'columnVisibility' => $this->columnVisibility,
            'selectedDepartments' => $this->selectedDepartments,
            'selectPage' => $this->selectPage,
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
            'branchFilter' => $this->branchFilter,
        ])->layoutData([
            'pageTitle' => 'Human Resource',
            'showBrand' => false,
            'navLinks' => HumanResourceNavigation::links('people', 'departments'),
        ]);
    }
}
