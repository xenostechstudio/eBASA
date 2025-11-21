<?php

namespace App\Livewire\HumanResource\Employments;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal')]
class Create extends Component
{
    /**
     * @var array<string, mixed>
     */
    public array $form = [
        'employee_id' => null,
        'branch_id' => null,
        'department_id' => null,
        'position_id' => null,
        'employment_type' => '',
        'employment_class' => '',
        'work_mode' => '',
        'status' => 'active',
        'salary_band' => '',
        'start_date' => null,
        'probation_end_date' => null,
        'notes' => '',
    ];

    public array $employees = [];
    public array $branches = [];
    public array $departments = [];
    public array $positions = [];

    public array $employmentTypes = ['full_time', 'part_time', 'contract'];
    public array $employmentClasses = ['permanent', 'probation', 'seasonal'];
    public array $workModes = ['onsite', 'hybrid', 'remote'];
    public array $statuses = ['active', 'on_leave', 'probation'];

    public function mount(): void
    {
        $this->employees = Employee::orderBy('full_name')->get(['id', 'full_name', 'code'])->all();
        $this->branches = Branch::orderBy('name')->get(['id', 'name'])->all();
        $this->departments = Department::orderBy('name')->get(['id', 'name'])->all();
        $this->positions = Position::orderBy('title')->get(['id', 'title'])->all();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes())['form'];

        $employee = Employee::findOrFail($validated['employee_id']);

        $employee->fill([
            'branch_id' => $validated['branch_id'],
            'department_id' => $validated['department_id'],
            'position_id' => $validated['position_id'],
            'employment_type' => $validated['employment_type'],
            'employment_class' => $validated['employment_class'],
            'work_mode' => $validated['work_mode'],
            'status' => $validated['status'],
            'salary_band' => $validated['salary_band'],
            'start_date' => $validated['start_date'],
            'probation_end_date' => $validated['probation_end_date'],
            'notes' => $validated['notes'],
        ])->save();

        session()->flash('status', 'Employment record updated');

        $this->dispatch('notify', message: 'Employment saved');

        redirect()->route('hr.employments');
    }

    public function rules(): array
    {
        return [
            'form.employee_id' => ['required', 'integer', Rule::exists('employees', 'id')],
            'form.branch_id' => ['required', 'integer', Rule::exists('branches', 'id')],
            'form.department_id' => ['nullable', 'integer', Rule::exists('departments', 'id')],
            'form.position_id' => ['nullable', 'integer', Rule::exists('positions', 'id')],
            'form.employment_type' => ['required', Rule::in($this->employmentTypes)],
            'form.employment_class' => ['nullable', Rule::in($this->employmentClasses)],
            'form.work_mode' => ['nullable', Rule::in($this->workModes)],
            'form.status' => ['required', Rule::in($this->statuses)],
            'form.salary_band' => ['nullable', 'string', 'max:50'],
            'form.start_date' => ['required', 'date'],
            'form.probation_end_date' => ['nullable', 'date', 'after_or_equal:form.start_date'],
            'form.notes' => ['nullable', 'string'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.employee_id' => 'employee',
            'form.branch_id' => 'branch',
            'form.department_id' => 'department',
            'form.position_id' => 'position',
            'form.employment_type' => 'employment type',
            'form.status' => 'status',
            'form.start_date' => 'start date',
        ];
    }

    public function render(): View
    {
        return view('livewire.hr.employments.create', [
            'employees' => $this->employees,
            'branches' => $this->branches,
            'departments' => $this->departments,
            'positions' => $this->positions,
            'employmentTypes' => $this->employmentTypes,
            'employmentClasses' => $this->employmentClasses,
            'workModes' => $this->workModes,
            'statuses' => $this->statuses,
        ])->layoutData([
            'pageTitle' => 'Human Resource',
            'showBrand' => false,
            'navLinks' => HumanResourceNavigation::links('people', 'employments'),
        ]);
    }
}
