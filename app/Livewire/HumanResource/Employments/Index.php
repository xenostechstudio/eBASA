<?php

namespace App\Livewire\HumanResource\Employments;

use App\Models\Employee;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public string $sortField = 'start_date';
    public string $sortDirection = 'desc';

    /** @var array<int, int> */
    public array $selectedEmployments = [];
    public bool $selectPage = false;

    /** @var array<int, int> */
    public array $pageEmploymentIds = [];

    /** @var array<string, bool> */
    public array $columnVisibility = [
        'employee' => true,
        'branch' => true,
        'department' => true,
        'position' => true,
        'class' => true,
        'start' => true,
        'status' => true,
    ];

    protected string $columnVisibilitySessionKey = 'hr.employments.columns';

    public function mount(): void
    {
        $storedVisibility = session($this->columnVisibilitySessionKey);

        if (is_array($storedVisibility)) {
            foreach ($this->columnVisibility as $column => $value) {
                if (array_key_exists($column, $storedVisibility)) {
                    $this->columnVisibility[$column] = (bool) $storedVisibility[$column];
                }
            }
        }
    }

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

    public function resetColumns(): void
    {
        $this->columnVisibility = [
            'employee' => true,
            'branch' => true,
            'department' => true,
            'position' => true,
            'class' => true,
            'start' => true,
            'status' => true,
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
            $this->selectedEmployments = array_map('strval', $this->pageEmploymentIds);
            return;
        }

        $this->selectedEmployments = [];
    }

    public function selectPage(): void
    {
        $this->selectedEmployments = array_map('strval', $this->pageEmploymentIds);
        $this->selectPage = $this->pageHasAllSelected();
    }

    public function selectAllEmployments(): void
    {
        $activeBranchId = (int) session('active_branch_id', 0) ?: null;

        $query = $this->scopedEmployees($activeBranchId);

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('full_name', 'like', '%'.$this->search.'%')
                    ->orWhere('code', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $allIds = $query->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->selectedEmployments = array_map('strval', $allIds);
        $this->selectPage = $this->pageHasAllSelected();
    }

    public function deselectAll(): void
    {
        $this->resetSelection();
    }

    public function deleteSelected(): void
    {
        if (empty($this->selectedEmployments)) {
            return;
        }

        Employee::query()
            ->whereIn('id', array_map('intval', $this->selectedEmployments))
            ->delete();

        $this->resetSelection();
        $this->dispatch('notify', message: 'Selected records deleted');
    }

    public function updatedSelectedEmployments(): void
    {
        $normalized = array_values(array_unique(array_map('intval', $this->selectedEmployments)));
        $this->selectedEmployments = array_map('strval', $normalized);
        $this->selectPage = $this->pageHasAllSelected($normalized);
    }

    protected function resetSelection(): void
    {
        $this->selectedEmployments = [];
        $this->selectPage = false;
    }

    protected function pageHasAllSelected(?array $selectedIds = null): bool
    {
        $selectedIds ??= array_values(array_unique(array_map('intval', $this->selectedEmployments)));

        if (empty($this->pageEmploymentIds)) {
            return false;
        }

        return empty(array_diff($this->pageEmploymentIds, $selectedIds));
    }

    protected function scopedEmployees(?int $branchId = null): Builder
    {
        return Employee::query()->when($branchId, function (Builder $query) use ($branchId) {
            $query->where('branch_id', $branchId);
        });
    }

    public function render(): View
    {
        $activeBranchId = (int) session('active_branch_id', 0) ?: null;

        $employmentsQuery = $this->scopedEmployees($activeBranchId)
            ->with(['branch', 'department', 'position']);

        if ($this->search !== '') {
            $employmentsQuery->where(function ($query) {
                $query->where('full_name', 'like', '%'.$this->search.'%')
                    ->orWhere('code', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $employmentsQuery->where('status', $this->statusFilter);
        }

        $allowedSorts = ['full_name', 'start_date', 'status'];
        $sortField = in_array($this->sortField, $allowedSorts, true) ? $this->sortField : 'start_date';

        $employments = $employmentsQuery
            ->orderBy($sortField, $this->sortDirection)
            ->paginate(10);

        $this->pageEmploymentIds = $employments->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->selectPage = $this->pageHasAllSelected();

        $stats = [
            'permanent' => Employee::where('employment_class', 'permanent')->count(),
            'probation' => Employee::where('employment_class', 'probation')->count(),
            'contract' => Employee::where('employment_type', 'contract')->count(),
        ];

        return view('livewire.hr.employments.index', [
            'employments' => $employments,
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Employment Records',
            'pageTagline' => 'HR · People',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('people', 'employments'),
        ]);
    }
}
