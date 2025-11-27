<?php

namespace App\Livewire\HumanResource\Employees;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Support\HumanResourceNavigation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public string $departmentFilter = 'all';
    public string $positionFilter = 'all';
    public string $sortField = 'full_name';
    public string $sortDirection = 'asc';
    public int $formStep = 1;
    public int $perPage = 10;
    public array $perPageOptions = [10, 25, 50, 100, 200];
    public string $viewType = 'list'; // 'list' or 'grid'

    /** @var array<int, int> */
    public array $selectedEmployees = [];
    public bool $selectPage = false;

    /** @var array<int, int> */
    public array $pageEmployeeIds = [];

    /** @var array<string, bool> */
    public array $columnVisibility = [
        'employee' => true,
        'department' => true,
        'position' => true,
        'status' => true,
        'dates' => true,
    ];

    protected string $columnVisibilitySessionKey = 'hr.employees.directory.columns';

    public function mount(): void
    {
        $storedVisibility = session($this->columnVisibilitySessionKey);

        if (! is_array($storedVisibility)) {
            return;
        }

        foreach ($this->columnVisibility as $column => $visible) {
            if (array_key_exists($column, $storedVisibility)) {
                $this->columnVisibility[$column] = (bool) $storedVisibility[$column];
            }
        }
    }

    public function resetColumns(): void
    {
        $this->columnVisibility = [
            'employee' => true,
            'department' => true,
            'position' => true,
            'status' => true,
            'dates' => true,
        ];

        session()->forget($this->columnVisibilitySessionKey);
        $this->persistColumnVisibility();
    }

    public function updated(string $property, $value): void
    {
        if (in_array($property, ['search', 'statusFilter', 'departmentFilter', 'positionFilter'], true)) {
            $this->resetPageState();
        }
    }

    public function updatingPage(): void
    {
        $this->resetSelection();
    }

    protected function resetPageState(): void
    {
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

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
    }

    public function setDepartmentFilter(string $department): void
    {
        $this->departmentFilter = $department;
    }

    public function setPositionFilter(string $position): void
    {
        $this->positionFilter = $position;
    }

    public function updatedColumnVisibility($value, string $key): void
    {
        if (array_key_exists($key, $this->columnVisibility)) {
            $this->columnVisibility[$key] = (bool) $value;
            $this->persistColumnVisibility();
        }
    }

    protected function persistColumnVisibility(): void
    {
        session([$this->columnVisibilitySessionKey => $this->columnVisibility]);
    }

    public function goToStep(int $step): void
    {
        $this->formStep = max(1, min(3, $step));
    }

    public function nextStep(): void
    {
        $this->goToStep($this->formStep + 1);
    }

    public function previousStep(): void
    {
        $this->goToStep($this->formStep - 1);
    }

    public function saveDraft(): void
    {
        $this->dispatch('notify', message: 'Draft saved');
    }

    public function setViewType(string $type): void
    {
        $this->viewType = in_array($type, ['list', 'grid'], true) ? $type : 'list';
    }

    public function toggleViewType(): void
    {
        $this->viewType = $this->viewType === 'grid' ? 'list' : 'grid';
    }

    public function updatedPerPage($value): void
    {
        $value = (int) $value;
        $this->perPage = in_array($value, $this->perPageOptions, true) ? $value : $this->perPageOptions[0];
        $this->resetPage();
        $this->resetSelection();
    }

    public function toggleSelectPage(): void
    {
        $this->selectPage = ! $this->selectPage;

        if ($this->selectPage) {
            // Select all employees on the current page
            $this->selectedEmployees = $this->stringifyIds($this->pageEmployeeIds);

            return;
        }

        // Clear selection when header checkbox is turned off
        $this->selectedEmployees = [];
    }

    public function updatedSelectedEmployees($value): void
    {
        $selectedIds = $this->normalizedSelectedIds();
        $this->selectedEmployees = $this->stringifyIds($selectedIds);
        $this->selectPage = $this->pageHasAllSelected($selectedIds);
    }

    public function bulkDelete(): void
    {
        if (empty($this->selectedEmployees)) {
            return;
        }

        Employee::query()
            ->whereIn('id', $this->normalizedSelectedIds())
            ->delete();

        $this->resetSelection();
        $this->dispatch('notify', message: 'Selected employees deleted');
    }

    public function selectAllOnPage(): void
    {
        $this->selectedEmployees = $this->stringifyIds($this->pageEmployeeIds);
        $this->selectPage = $this->pageHasAllSelected();
    }

    public function clearSelection(): void
    {
        $this->resetSelection();
    }

    public function resetFilters(): void
    {
        $this->statusFilter = 'all';
        $this->departmentFilter = 'all';
        $this->positionFilter = 'all';

        $this->resetPageState();
    }

    protected function scopedEmployees(?int $branchId = null): Builder
    {
        return Employee::query()->when($branchId, function (Builder $query) use ($branchId) {
            $query->where('branch_id', $branchId);
        });
    }

    public function render(): View
    {
        $activeBranchId = (int) session('active_branch_id', 0);
        $activeBranchId = $activeBranchId > 0 ? $activeBranchId : null;

        $employeesQuery = $this->scopedEmployees($activeBranchId)->with(['branch', 'department', 'position']);

        if ($this->search !== '') {
            $employeesQuery->where(function ($query) {
                $query->where('full_name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('code', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $employeesQuery->where('status', $this->statusFilter);
        }

        if ($this->departmentFilter !== 'all') {
            $employeesQuery->where('department_id', (int) $this->departmentFilter);
        }

        if ($this->positionFilter !== 'all') {
            $employeesQuery->where('position_id', (int) $this->positionFilter);
        }

        $allowedSorts = ['full_name', 'start_date', 'status', 'code', 'department', 'position'];
        $sortField = in_array($this->sortField, $allowedSorts, true) ? $this->sortField : 'full_name';

        $employees = match ($sortField) {
            'department' => $employeesQuery
                ->orderBy(
                    Department::select('name')->whereColumn('departments.id', 'employees.department_id'),
                    $this->sortDirection
                )
                ->paginate($this->perPage),
            'position' => $employeesQuery
                ->orderBy(
                    Position::select('title')->whereColumn('positions.id', 'employees.position_id'),
                    $this->sortDirection
                )
                ->paginate($this->perPage),
            default => $employeesQuery
                ->orderBy($sortField, $this->sortDirection)
                ->paginate($this->perPage),
        };

        $this->pageEmployeeIds = $employees->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->selectPage = $this->pageHasAllSelected();

        $statsBase = $this->scopedEmployees($activeBranchId);
        $stats = [
            'total' => (clone $statsBase)->count(),
            'active' => (clone $statsBase)->where('status', 'active')->count(),
            'on_leave' => (clone $statsBase)->where('status', 'on_leave')->count(),
            'probation' => (clone $statsBase)->where('status', 'probation')->count(),
        ];

        /** @noinspection PhpUndefinedMethodInspection */
        return view('livewire.hr.employees.index', [
            'employees' => $employees,
            'branches' => Branch::orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'positions' => Position::orderBy('title')->get(),
            'stats' => $stats,
            'activeBranchId' => $activeBranchId,
        ])->layoutData([
            'pageTitle' => 'Human Resource',
            'showBrand' => false,
            'navLinks' => HumanResourceNavigation::links('people', 'employees'),
        ]);
    }

    protected function resetSelection(): void
    {
        $this->selectedEmployees = [];
        $this->selectPage = false;
    }

    protected function pageHasAllSelected(?array $selectedIds = null): bool
    {
        $selectedIds ??= $this->normalizedSelectedIds();

        if (empty($this->pageEmployeeIds)) {
            return false;
        }

        return empty(array_diff($this->pageEmployeeIds, $selectedIds));
    }

    protected function normalizedSelectedIds(): array
    {
        return array_values(array_unique(array_map('intval', $this->selectedEmployees)));
    }

    /**
     * @param array<int, int|string> $ids
     * @return array<int, string>
     */
    protected function stringifyIds(array $ids): array
    {
        return array_values(array_map(static fn ($id) => (string) $id, $ids));
    }
}
