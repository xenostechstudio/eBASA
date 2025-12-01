<?php

namespace App\Livewire\HumanResource\Leave\LeaveTypes;

use App\Models\LeaveType;
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
        $leaveTypes = LeaveType::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.hr.leave.leave-types.index', [
            'leaveTypes' => $leaveTypes,
        ])->layoutData([
            'pageTitle' => 'Leave Types',
            'pageTagline' => 'HR · Leave Management',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('leave', 'leave-types'),
        ]);
    }
}
