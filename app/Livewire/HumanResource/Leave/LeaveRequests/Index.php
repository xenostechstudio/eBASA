<?php

namespace App\Livewire\HumanResource\Leave\LeaveRequests;

use App\Models\LeaveRequest;
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
        $leaveRequests = LeaveRequest::query()
            ->with(['employee.position', 'leaveType', 'createdBy'])
            ->when($this->search, fn ($q) => $q->whereHas('employee', fn ($e) => $e->where('full_name', 'like', "%{$this->search}%"))
                ->orWhere('code', 'like', "%{$this->search}%"))
            ->orderByDesc('created_at')
            ->paginate(15);

        $pendingCount = LeaveRequest::where('status', 'pending')->count();
        $approvedCount = LeaveRequest::where('status', 'approved')->count();
        $rejectedCount = LeaveRequest::where('status', 'rejected')->count();
        $totalCount = LeaveRequest::count();

        return view('livewire.hr.leave.leave-requests.index', [
            'leaveRequests' => $leaveRequests,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'totalCount' => $totalCount,
        ])->layoutData([
            'pageTitle' => 'Leave Requests',
            'pageTagline' => 'HR · Leave Management',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('leave', 'leave-requests'),
        ]);
    }
}
