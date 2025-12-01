<?php

namespace App\Livewire\HumanResource\Attendance\Attendances;

use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Shift;
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
    public string $filterStatus = '';

    #[Url]
    public string $filterShift = '';

    #[Url]
    public string $filterBranch = '';

    #[Url]
    public string $filterDate = '';

    public function mount(): void
    {
        $this->filterDate = now()->format('Y-m-d');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatedFilterShift(): void
    {
        $this->resetPage();
    }

    public function updatedFilterBranch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterDate(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->filterStatus = '';
        $this->filterShift = '';
        $this->filterBranch = '';
        $this->filterDate = now()->format('Y-m-d');
        $this->resetPage();
    }

    public function getActiveFiltersCountProperty(): int
    {
        return collect([
            $this->filterStatus,
            $this->filterShift,
            $this->filterBranch,
        ])->filter()->count();
    }

    public function render(): View
    {
        $attendances = Attendance::query()
            ->with(['employee', 'shift', 'branch'])
            ->when($this->search, fn ($q) => $q->whereHas('employee', fn ($e) => $e->where('full_name', 'like', "%{$this->search}%")))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterShift, fn ($q) => $q->where('shift_id', $this->filterShift))
            ->when($this->filterBranch, fn ($q) => $q->where('branch_id', $this->filterBranch))
            ->when($this->filterDate, fn ($q) => $q->whereDate('date', $this->filterDate))
            ->orderByDesc('date')
            ->orderBy('clock_in')
            ->paginate(20);

        $shifts = Shift::where('is_active', true)->orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();
        $statuses = ['present', 'absent', 'late', 'half_day', 'leave', 'holiday', 'weekend'];

        // Stats for the day
        $dateStats = Attendance::whereDate('date', $this->filterDate ?: now()->format('Y-m-d'));
        $presentCount = (clone $dateStats)->where('status', 'present')->count();
        $lateCount = (clone $dateStats)->where('status', 'late')->count();
        $absentCount = (clone $dateStats)->where('status', 'absent')->count();
        $leaveCount = (clone $dateStats)->where('status', 'leave')->count();

        return view('livewire.hr.attendance.attendances.index', [
            'attendances' => $attendances,
            'shifts' => $shifts,
            'branches' => $branches,
            'statuses' => $statuses,
            'activeFiltersCount' => $this->activeFiltersCount,
            'presentCount' => $presentCount,
            'lateCount' => $lateCount,
            'absentCount' => $absentCount,
            'leaveCount' => $leaveCount,
        ])->layoutData([
            'pageTitle' => 'Daily Attendance',
            'pageTagline' => 'HR · Attendance',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('attendance', 'attendances'),
        ]);
    }
}
