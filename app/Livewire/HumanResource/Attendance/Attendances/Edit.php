<?php

namespace App\Livewire\HumanResource\Attendance\Attendances;

use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Shift;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    public Attendance $attendance;
    public array $form = [];

    public function mount(Attendance $attendance): void
    {
        $this->attendance = $attendance->load(['employee', 'shift', 'branch']);
        $this->form = [
            'employee_id' => $attendance->employee_id,
            'shift_id' => $attendance->shift_id,
            'branch_id' => $attendance->branch_id,
            'date' => $attendance->date?->format('Y-m-d'),
            'clock_in' => $attendance->clock_in?->format('H:i'),
            'clock_out' => $attendance->clock_out?->format('H:i'),
            'late_minutes' => $attendance->late_minutes,
            'early_leave_minutes' => $attendance->early_leave_minutes,
            'overtime_minutes' => $attendance->overtime_minutes,
            'status' => $attendance->status,
            'notes' => $attendance->notes,
        ];
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];

        // Calculate worked minutes if both clock in and out are provided
        if (!empty($validated['clock_in']) && !empty($validated['clock_out'])) {
            $clockIn = \Carbon\Carbon::parse($validated['clock_in']);
            $clockOut = \Carbon\Carbon::parse($validated['clock_out']);
            $validated['worked_minutes'] = $clockOut->diffInMinutes($clockIn);
        }

        $this->attendance->update($validated);

        session()->flash('status', 'Attendance record updated successfully.');
        $this->redirect(route('hr.attendances'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.employee_id' => ['required', 'integer', 'exists:employees,id'],
            'form.shift_id' => ['nullable', 'integer', 'exists:shifts,id'],
            'form.branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'form.date' => ['required', 'date'],
            'form.clock_in' => ['nullable', 'date_format:H:i'],
            'form.clock_out' => ['nullable', 'date_format:H:i'],
            'form.late_minutes' => ['nullable', 'integer', 'min:0'],
            'form.early_leave_minutes' => ['nullable', 'integer', 'min:0'],
            'form.overtime_minutes' => ['nullable', 'integer', 'min:0'],
            'form.status' => ['required', 'in:present,absent,late,half_day,leave,holiday,weekend'],
            'form.notes' => ['nullable', 'string'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.employee_id' => 'employee',
            'form.shift_id' => 'shift',
            'form.branch_id' => 'branch',
            'form.date' => 'date',
            'form.clock_in' => 'clock in',
            'form.clock_out' => 'clock out',
        ];
    }

    public function render(): View
    {
        $employees = Employee::orderBy('full_name')->get(['id', 'full_name', 'code']);
        $shifts = Shift::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']);
        $branches = Branch::orderBy('name')->get(['id', 'name']);
        $statuses = ['present', 'absent', 'late', 'half_day', 'leave', 'holiday', 'weekend'];

        return view('livewire.hr.attendance.attendances.edit', [
            'attendance' => $this->attendance,
            'employees' => $employees,
            'shifts' => $shifts,
            'branches' => $branches,
            'statuses' => $statuses,
        ])->layoutData([
            'pageTitle' => 'Edit Attendance',
            'pageTagline' => 'HR · Attendance',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('attendance', 'attendances'),
        ]);
    }
}
