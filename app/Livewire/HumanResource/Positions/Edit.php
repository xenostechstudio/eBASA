<?php

namespace App\Livewire\HumanResource\Positions;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Position;
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

    public Position $position;

    /**
     * @var array<string, mixed>
     */
    public array $form = [];

    public array $branches = [];
    public array $departments = [];

    // Relation manager state
    public string $employeesSearch = '';

    public function mount(Position $position): void
    {
        $this->position = $position->load(['department', 'branch']);

        $this->form = [
            'code' => $position->code,
            'title' => $position->title,
            'level' => $position->level,
            'job_family' => $position->job_family,
            'is_people_manager' => (bool) $position->is_people_manager,
            'department_id' => $position->department_id,
            'branch_id' => $position->branch_id,
            'description' => $position->description,
        ];

        $this->branches = Branch::orderBy('name')->get(['id', 'name'])->all();
        $this->departments = Department::orderBy('name')->get(['id', 'name'])->all();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes());

        $this->position->update($validated['form']);

        session()->flash('status', 'Position updated successfully');
        $this->dispatch('notify', message: 'Position updated');
    }

    #[Computed]
    public function stats(): array
    {
        return [
            'employeesCount' => $this->position->employees()->count(),
            'activeEmployees' => $this->position->employees()->where('status', 'active')->count(),
            'department' => $this->position->department?->name ?? '-',
            'branch' => $this->position->branch?->name ?? '-',
        ];
    }

    #[Computed]
    public function employees()
    {
        return $this->position->employees()
            ->with(['department', 'branch'])
            ->when($this->employeesSearch, fn ($q) => $q->where('full_name', 'like', "%{$this->employeesSearch}%"))
            ->orderBy('full_name')
            ->paginate(10, pageName: 'employeesPage');
    }

    public function goToEmployee(int $employeeId): void
    {
        $this->redirect(route('hr.employees.edit', $employeeId), navigate: true);
    }

    public function rules(): array
    {
        return [
            'form.code' => ['required', 'string', 'max:50', Rule::unique('positions', 'code')->ignore($this->position->id)],
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
        return view('livewire.hr.positions.edit')->layoutData([
            'pageTitle' => 'Edit Position',
            'pageTagline' => 'HR · People',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('people', 'positions'),
        ]);
    }
}
