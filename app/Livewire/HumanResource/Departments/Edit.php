<?php

namespace App\Livewire\HumanResource\Departments;

use App\Models\Branch;
use App\Models\Department;
use App\Support\HumanResourceNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    use WithPagination;

    public Department $department;

    /**
     * @var array<string, mixed>
     */
    public array $form = [];

    public array $branches = [];
    public array $allDepartments = [];

    // Relation manager state
    public string $positionsSearch = '';
    public string $employeesSearch = '';

    public function mount(Department $department): void
    {
        $this->department = $department->load(['branch', 'parent']);

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
        $this->allDepartments = Department::where('id', '!=', $department->id)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->all();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes());

        $this->department->update($validated['form']);

        session()->flash('status', 'Department updated successfully');
        $this->dispatch('notify', message: 'Department updated');
    }

    #[Computed]
    public function stats(): array
    {
        return [
            'positionsCount' => $this->department->positions()->count(),
            'employeesCount' => $this->department->employees()->count(),
            'activeEmployees' => $this->department->employees()->where('status', 'active')->count(),
            'branch' => $this->department->branch?->name ?? '-',
        ];
    }

    #[Computed]
    public function positions()
    {
        return $this->department->positions()
            ->with('branch')
            ->when($this->positionsSearch, fn ($q) => $q->where('title', 'like', "%{$this->positionsSearch}%"))
            ->orderBy('title')
            ->paginate(10, pageName: 'positionsPage');
    }

    #[Computed]
    public function employees()
    {
        return $this->department->employees()
            ->with(['position', 'branch'])
            ->when($this->employeesSearch, fn ($q) => $q->where('full_name', 'like', "%{$this->employeesSearch}%"))
            ->orderBy('full_name')
            ->paginate(10, pageName: 'employeesPage');
    }

    public function goToPosition(int $positionId): void
    {
        $this->redirect(route('hr.positions.edit', $positionId), navigate: true);
    }

    public function goToEmployee(int $employeeId): void
    {
        $this->redirect(route('hr.employees.edit', $employeeId), navigate: true);
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
        return view('livewire.hr.departments.edit')->layoutData([
            'pageTitle' => 'Edit Department',
            'pageTagline' => 'HR · People',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('people', 'departments'),
        ]);
    }
}
