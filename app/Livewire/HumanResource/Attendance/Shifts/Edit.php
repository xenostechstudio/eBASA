<?php

namespace App\Livewire\HumanResource\Attendance\Shifts;

use App\Models\Shift;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    public Shift $shift;
    public array $form = [];

    public function mount(Shift $shift): void
    {
        $this->shift = $shift;
        $this->form = [
            'code' => $shift->code,
            'name' => $shift->name,
            'start_time' => $shift->start_time?->format('H:i'),
            'end_time' => $shift->end_time?->format('H:i'),
            'break_start' => $shift->break_start?->format('H:i'),
            'break_end' => $shift->break_end?->format('H:i'),
            'break_duration' => $shift->break_duration,
            'working_hours' => $shift->working_hours,
            'is_overnight' => $shift->is_overnight,
            'is_active' => $shift->is_active,
            'description' => $shift->description,
        ];
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];

        $this->shift->update($validated);

        session()->flash('status', 'Shift updated successfully.');
        $this->redirect(route('hr.shifts'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('shifts', 'code')->ignore($this->shift->id)],
            'form.name' => ['required', 'string', 'max:255'],
            'form.start_time' => ['required', 'date_format:H:i'],
            'form.end_time' => ['required', 'date_format:H:i'],
            'form.break_start' => ['nullable', 'date_format:H:i'],
            'form.break_end' => ['nullable', 'date_format:H:i'],
            'form.break_duration' => ['required', 'integer', 'min:0'],
            'form.working_hours' => ['required', 'integer', 'min:1', 'max:24'],
            'form.is_overnight' => ['boolean'],
            'form.is_active' => ['boolean'],
            'form.description' => ['nullable', 'string'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.code' => 'code',
            'form.name' => 'name',
            'form.start_time' => 'start time',
            'form.end_time' => 'end time',
            'form.break_start' => 'break start',
            'form.break_end' => 'break end',
            'form.break_duration' => 'break duration',
            'form.working_hours' => 'working hours',
        ];
    }

    public function render(): View
    {
        return view('livewire.hr.attendance.shifts.edit', [
            'shift' => $this->shift,
        ])->layoutData([
            'pageTitle' => 'Edit Shift',
            'pageTagline' => 'HR · Attendance',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('attendance', 'shifts'),
        ]);
    }
}
