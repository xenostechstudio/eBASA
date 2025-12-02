<?php

namespace App\Livewire\HumanResource\Payroll\PayrollRuns;

use App\Models\Branch;
use App\Models\PayrollGroup;
use App\Models\PayrollRun;
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
    public string $filterPayrollGroup = '';

    #[Url]
    public string $filterBranch = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatedFilterPayrollGroup(): void
    {
        $this->resetPage();
    }

    public function updatedFilterBranch(): void
    {
        $this->resetPage();
    }

    public function setStatusFilter(string $value): void
    {
        $this->filterStatus = $value === 'all' ? '' : $value;
        $this->resetPage();
    }

    public function setPayrollGroupFilter(string $value): void
    {
        $this->filterPayrollGroup = $value === 'all' ? '' : $value;
        $this->resetPage();
    }

    public function setBranchFilter(string $value): void
    {
        $this->filterBranch = $value === 'all' ? '' : $value;
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->filterStatus = '';
        $this->filterPayrollGroup = '';
        $this->filterBranch = '';
        $this->resetPage();
    }

    public function getActiveFiltersCountProperty(): int
    {
        return collect([
            $this->filterStatus,
            $this->filterPayrollGroup,
            $this->filterBranch,
        ])->filter()->count();
    }

    public function render(): View
    {
        $runs = PayrollRun::query()
            ->with(['payrollGroup', 'branch', 'createdBy'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterPayrollGroup, fn ($q) => $q->where('payroll_group_id', $this->filterPayrollGroup))
            ->when($this->filterBranch, fn ($q) => $q->where('branch_id', $this->filterBranch))
            ->orderByDesc('period_start')
            ->paginate(15);

        $payrollGroups = PayrollGroup::where('is_active', true)->orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();
        $statuses = ['draft', 'processing', 'approved', 'paid', 'cancelled'];

        return view('livewire.hr.payroll.payroll-runs.index', [
            'runs' => $runs,
            'payrollGroups' => $payrollGroups,
            'branches' => $branches,
            'statuses' => $statuses,
            'activeFiltersCount' => $this->activeFiltersCount,
        ])->layoutData([
            'pageTitle' => 'Payroll Runs',
            'pageTagline' => 'HR · Payroll',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('payroll', 'payroll-runs'),
        ]);
    }
}
