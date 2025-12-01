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
class Create extends Component
{
    /**
     * @var array<string, mixed>
     */
    public array $form = [
        'code' => '',
        'name' => '',
        'branch_id' => null,
        'parent_id' => null,
        'lead_name' => '',
        'lead_email' => '',
        'description' => '',
    ];

    public array $branches = [];
    public array $departments = [];

    public function mount(): void
    {
        $this->branches = Branch::orderBy('name')->get(['id', 'name'])->all();
        $this->departments = Department::orderBy('name')->get(['id', 'name'])->all();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes());

        Department::create($validated['form']);

        session()->flash('status', 'Department created successfully');
        $this->dispatch('notify', message: 'Department created');

        redirect()->route('hr.departments');
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('departments', 'code')],
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
        return view('livewire.hr.departments.create', [
            'branches' => $this->branches,
            'departments' => $this->departments,
        ])->layoutData([
            'pageTitle' => 'New Department',
            'pageTagline' => 'HR · People',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('people', 'departments'),
        ]);
    }
}
