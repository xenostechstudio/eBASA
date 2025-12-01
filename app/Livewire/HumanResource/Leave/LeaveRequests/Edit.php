<?php

namespace App\Livewire\HumanResource\Leave\LeaveRequests;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    public LeaveRequest $leaveRequest;
    public array $form = [];
    public array $employees = [];
    public array $leaveTypes = [];
    public array $statuses = ['pending', 'approved', 'rejected', 'cancelled'];

    public function mount(LeaveRequest $leaveRequest): void
    {
        $this->leaveRequest = $leaveRequest;
        $this->form = [
            'code' => $leaveRequest->code,
            'employee_id' => $leaveRequest->employee_id,
            'leave_type_id' => $leaveRequest->leave_type_id,
            'start_date' => $leaveRequest->start_date?->format('Y-m-d'),
            'end_date' => $leaveRequest->end_date?->format('Y-m-d'),
            'total_days' => $leaveRequest->total_days,
            'reason' => $leaveRequest->reason,
            'status' => $leaveRequest->status,
        ];
        $this->employees = Employee::orderBy('full_name')->get(['id', 'full_name', 'code'])->toArray();
        $this->leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code'])->toArray();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];
        $this->leaveRequest->update($validated);
        session()->flash('status', 'Leave request updated successfully');
        $this->redirect(route('hr.leave-requests'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('leave_requests', 'code')->ignore($this->leaveRequest->id)],
            'form.employee_id' => ['required', 'integer', 'exists:employees,id'],
            'form.leave_type_id' => ['required', 'integer', 'exists:leave_types,id'],
            'form.start_date' => ['required', 'date'],
            'form.end_date' => ['required', 'date', 'after_or_equal:form.start_date'],
            'form.total_days' => ['required', 'numeric', 'min:0.5'],
            'form.reason' => ['nullable', 'string'],
            'form.status' => ['required', Rule::in($this->statuses)],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.code' => 'code',
            'form.employee_id' => 'employee',
            'form.leave_type_id' => 'leave type',
            'form.start_date' => 'start date',
            'form.end_date' => 'end date',
            'form.total_days' => 'total days',
        ];
    }

    public function render(): View
    {
        return view('livewire.hr.leave.leave-requests.edit', [
            'employees' => $this->employees,
            'leaveTypes' => $this->leaveTypes,
            'statuses' => $this->statuses,
            'leaveRequest' => $this->leaveRequest,
        ])->layoutData([
            'pageTitle' => 'Edit Leave Request',
            'pageTagline' => 'HR · Leave Management',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('leave', 'leave-requests'),
        ]);
    }
}
