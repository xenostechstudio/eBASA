<?php

namespace App\Livewire\HumanResource\Payroll\PayrollPayouts;

use App\Models\PayrollPayout;
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
        $payouts = PayrollPayout::query()
            ->with(['employee', 'payrollRun', 'createdBy'])
            ->when($this->search, fn ($q) => $q->whereHas('employee', fn ($e) => $e->where('full_name', 'like', "%{$this->search}%"))
                ->orWhere('code', 'like', "%{$this->search}%"))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.hr.payroll.payroll-payouts.index', [
            'payouts' => $payouts,
        ])->layoutData([
            'pageTitle' => 'Payroll Payouts',
            'pageTagline' => 'HR · Payroll',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('payroll', 'payroll-payouts'),
        ]);
    }
}
