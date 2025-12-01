<?php

namespace App\Livewire\HumanResource\Attendance\Shifts;

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

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->filterStatus = '';
        $this->resetPage();
    }

    public function getActiveFiltersCountProperty(): int
    {
        return collect([$this->filterStatus])->filter()->count();
    }

    public function delete(int $id): void
    {
        Shift::destroy($id);
        session()->flash('status', 'Shift deleted successfully.');
    }

    public function render(): View
    {
        $shifts = Shift::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->filterStatus !== '', fn ($q) => $q->where('is_active', $this->filterStatus === '1'))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.hr.attendance.shifts.index', [
            'shifts' => $shifts,
            'activeFiltersCount' => $this->activeFiltersCount,
        ])->layoutData([
            'pageTitle' => 'Shifts',
            'pageTagline' => 'HR · Attendance',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('attendance', 'shifts'),
        ]);
    }
}
