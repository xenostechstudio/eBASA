<?php

namespace App\Livewire\HumanResource\Attendance\Shifts;

use App\Models\Shift;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Create extends Component
{
    public array $form = [
        'code' => '',
        'name' => '',
        'start_time' => '08:00',
        'end_time' => '17:00',
        'break_start' => '12:00',
        'break_end' => '13:00',
        'break_duration' => 60,
        'working_hours' => 8,
        'is_overnight' => false,
        'is_active' => true,
        'description' => '',
    ];

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];

        Shift::create($validated);

        session()->flash('status', 'Shift created successfully.');
        $this->redirect(route('hr.shifts'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', 'unique:shifts,code'],
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
        return view('livewire.hr.attendance.shifts.create')->layoutData([
            'pageTitle' => 'Create Shift',
            'pageTagline' => 'HR · Attendance',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('attendance', 'shifts'),
        ]);
    }
}
