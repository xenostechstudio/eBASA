<?php

namespace App\Livewire\HumanResource\Departments;

use App\Models\Branch;
use App\Models\Department;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    public Department $department;

    /**
     * @var array<string, mixed>
     */
    public array $form = [];

    public array $branches = [];
    public array $departments = [];

    public function mount(Department $department): void
    {
        $this->department = $department;

        $this->form = [
            'code' => $department->code,
            'name' => $department->name,
            'branch_id' => $department->branch_id,
            'parent_id' => $department->parent_id,
            'lead_name' => $department->lead_name,
            'lead_email' => $department->lead_email,
            'description' => $department->description,
        ];

        $this->branches = Branch::orderBy('name')->get(['id', 'name'])->all();
        $this->departments = Department::orderBy('name')->get(['id', 'name'])->all();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes());

        $this->department->update($validated['form']);

        session()->flash('status', 'Department updated successfully');
        $this->dispatch('notify', message: 'Department updated');

        $this->redirect(route('hr.departments'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('departments', 'code')->ignore($this->department->id)],
            'form.name' => ['required', 'string', 'max:255'],
            'form.branch_id' => ['required', 'integer', 'exists:branches,id'],
            'form.parent_id' => ['nullable', 'integer', 'exists:departments,id'],
            'form.lead_name' => ['nullable', 'string', 'max:255'],
            'form.lead_email' => ['nullable', 'email', 'max:255'],
            'form.description' => ['nullable', 'string'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.code' => 'department code',
            'form.name' => 'department name',
            'form.branch_id' => 'branch',
            'form.parent_id' => 'parent department',
            'form.lead_name' => 'lead name',
            'form.lead_email' => 'lead email',
        ];
    }

    public function render(): View
    {
        return view('livewire.hr.departments.edit', [
            'branches' => $this->branches,
            'departments' => $this->departments,
            'department' => $this->department,
        ])->layoutData([
            'pageTitle' => 'Edit Department',
            'pageTagline' => 'HR · People',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('people', 'departments'),
        ]);
    }
}
