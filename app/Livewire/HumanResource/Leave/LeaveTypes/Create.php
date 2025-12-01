<?php

namespace App\Livewire\HumanResource\Leave\LeaveTypes;

use App\Models\LeaveType;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Create extends Component
{
    public array $form = [
        'code' => '',
        'name' => '',
        'description' => '',
        'default_days' => 0,
        'is_paid' => true,
        'requires_approval' => true,
        'requires_attachment' => false,
        'is_active' => true,
        'color' => '#3B82F6',
    ];

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];
        LeaveType::create($validated);
        session()->flash('status', 'Leave type created successfully');
        $this->redirect(route('hr.leave-types'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('leave_types', 'code')],
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
        return view('livewire.hr.leave.leave-types.create')->layoutData([
            'pageTitle' => 'New Leave Type',
            'pageTagline' => 'HR · Leave Management',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('leave', 'leave-types'),
        ]);
    }
}
