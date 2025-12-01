<?php

namespace App\Livewire\HumanResource\Leave\LeaveTypes;

use App\Models\LeaveType;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    public LeaveType $leaveType;
    public array $form = [];

    public function mount(LeaveType $leaveType): void
    {
        $this->leaveType = $leaveType;
        $this->form = [
            'code' => $leaveType->code,
            'name' => $leaveType->name,
            'description' => $leaveType->description,
            'default_days' => $leaveType->default_days,
            'is_paid' => $leaveType->is_paid,
            'requires_approval' => $leaveType->requires_approval,
            'requires_attachment' => $leaveType->requires_attachment,
            'is_active' => $leaveType->is_active,
            'color' => $leaveType->color,
        ];
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];
        $this->leaveType->update($validated);
        session()->flash('status', 'Leave type updated successfully');
        $this->redirect(route('hr.leave-types'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('leave_types', 'code')->ignore($this->leaveType->id)],
            'form.name' => ['required', 'string', 'max:255'],
            'form.description' => ['nullable', 'string'],
            'form.default_days' => ['required', 'integer', 'min:0'],
            'form.is_paid' => ['boolean'],
            'form.requires_approval' => ['boolean'],
            'form.requires_attachment' => ['boolean'],
            'form.is_active' => ['boolean'],
            'form.color' => ['required', 'string', 'max:7'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.code' => 'code',
            'form.name' => 'name',
            'form.default_days' => 'default days',
        ];
    }

    public function render(): View
    {
        return view('livewire.hr.leave.leave-types.edit', [
            'leaveType' => $this->leaveType,
        ])->layoutData([
            'pageTitle' => 'Edit Leave Type',
            'pageTagline' => 'HR · Leave Management',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('leave', 'leave-types'),
        ]);
    }
}
