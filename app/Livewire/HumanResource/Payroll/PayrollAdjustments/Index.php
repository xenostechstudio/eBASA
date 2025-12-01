<?php

namespace App\Livewire\HumanResource\Payroll\PayrollAdjustments;

use App\Models\PayrollAdjustment;
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

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $adjustments = PayrollAdjustment::query()
            ->with(['employee', 'payrollRun', 'createdBy'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%"))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.hr.payroll.payroll-adjustments.index', [
            'adjustments' => $adjustments,
        ])->layoutData([
            'pageTitle' => 'Payroll Adjustments',
            'pageTagline' => 'HR · Payroll',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('payroll', 'payroll-adjustments'),
        ]);
    }
}
