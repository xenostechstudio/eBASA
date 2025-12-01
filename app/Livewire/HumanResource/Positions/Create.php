<?php

namespace App\Livewire\HumanResource\Positions;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Position;
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
        'title' => '',
        'level' => '',
        'job_family' => '',
        'is_people_manager' => false,
        'department_id' => null,
        'branch_id' => null,
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

        Position::create($validated['form']);

        session()->flash('status', 'Position created successfully');
        $this->dispatch('notify', message: 'Position created');

        redirect()->route('hr.positions');
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('positions', 'code')],
            'form.title' => ['required', 'string', 'max:255'],
            'form.level' => ['nullable', 'string', 'max:120'],
            'form.job_family' => ['nullable', 'string', 'max:120'],
            'form.is_people_manager' => ['boolean'],
            'form.department_id' => ['required', 'integer', 'exists:departments,id'],
            'form.branch_id' => ['required', 'integer', 'exists:branches,id'],
            'form.description' => ['nullable', 'string'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.code' => 'position code',
            'form.title' => 'position title',
            'form.department_id' => 'department',
            'form.branch_id' => 'branch',
        ];
    }

    public function render(): View
    {
        return view('livewire.hr.positions.create', [
            'branches' => $this->branches,
            'departments' => $this->departments,
        ])->layoutData([
            'pageTitle' => 'New Position',
            'pageTagline' => 'HR · People',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('people', 'positions'),
        ]);
    }
}
