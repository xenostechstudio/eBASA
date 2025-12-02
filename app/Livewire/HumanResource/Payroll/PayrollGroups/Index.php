<?php

namespace App\Livewire\HumanResource\Payroll\PayrollGroups;

use App\Models\Branch;
use App\Models\PayrollGroup;
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
    public string $filterFrequency = '';

    #[Url]
    public string $filterBranch = '';

    #[Url]
    public string $filterStatus = '';

    public ?int $deleteId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterFrequency(): void
    {
        $this->resetPage();
    }

    public function updatedFilterBranch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function setFrequencyFilter(string $value): void
    {
        $this->filterFrequency = $value === 'all' ? '' : $value;
        $this->resetPage();
    }

    public function setBranchFilter(string $value): void
    {
        $this->filterBranch = $value === 'all' ? '' : $value;
        $this->resetPage();
    }

    public function setStatusFilter(string $value): void
    {
        $this->filterStatus = $value === 'all' ? '' : $value;
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->filterFrequency = '';
        $this->filterBranch = '';
        $this->filterStatus = '';
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    public function delete(): void
    {
        if ($this->deleteId) {
            PayrollGroup::destroy($this->deleteId);
            $this->deleteId = null;
            session()->flash('status', 'Payroll group deleted successfully.');
        }
    }

    public function render(): View
    {
        $groups = PayrollGroup::query()
            ->with(['branch', 'createdBy'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->filterFrequency, fn ($q) => $q->where('pay_frequency', $this->filterFrequency))
            ->when($this->filterBranch, fn ($q) => $q->where('branch_id', $this->filterBranch))
            ->when($this->filterStatus !== '', fn ($q) => $q->where('is_active', $this->filterStatus === '1'))
            ->orderBy('name')
            ->paginate(15);

        $branches = Branch::orderBy('name')->get(['id', 'name']);
        $frequencies = ['weekly', 'biweekly', 'monthly'];

        $activeFiltersCount = collect([$this->filterFrequency, $this->filterBranch, $this->filterStatus])
            ->filter(fn ($v) => $v !== '')
            ->count();

        return view('livewire.hr.payroll.payroll-groups.index', [
            'groups' => $groups,
            'branches' => $branches,
            'frequencies' => $frequencies,
            'activeFiltersCount' => $activeFiltersCount,
        ])->layoutData([
            'pageTitle' => 'Payroll Groups',
            'pageTagline' => 'HR · Payroll',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('payroll', 'payroll-groups'),
        ]);
    }
}
