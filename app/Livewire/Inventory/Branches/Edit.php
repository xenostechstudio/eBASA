<?php

namespace App\Livewire\Inventory\Branches;

use App\Models\Branch;
use App\Models\Employee;
use App\Support\GeneralSetupNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
#[Title('Edit Branch')]
class Edit extends Component
{
    public ?int $branchId = null;
    public ?Branch $editingBranch = null;

    public string $code = '';
    public string $name = '';
    public string $city = '';
    public string $province = '';
    public string $address = '';
    public string $phone = '';
    public string $email = '';
    public string $manager_name = '';
    public ?int $manager_employee_id = null;
    public bool $is_active = true;

    public function mount(Branch $branch): void
    {
        $this->branchId = $branch->id;
        $this->editingBranch = $branch->load(['createdBy', 'updatedBy']);

        $this->code = $branch->code ?? '';
        $this->name = $branch->name ?? '';
        $this->city = $branch->city ?? '';
        $this->province = $branch->province ?? '';
        $this->address = $branch->address ?? '';
        $this->phone = $branch->phone ?? '';
        $this->email = $branch->email ?? '';
        $this->manager_name = $branch->manager_name ?? '';
        $this->is_active = (bool) ($branch->is_active ?? true);

        // Pre-select manager based on existing manager_name if possible
        $this->manager_employee_id = null;

        if ($branch->manager_name) {
            $employee = Employee::where('status', 'active')
                ->where('full_name', $branch->manager_name)
                ->first();

            if ($employee) {
                $this->manager_employee_id = $employee->id;
            }
        }
    }

    protected function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('branches', 'code')->ignore($this->branchId)],
            'name' => ['required', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'province' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:120'],
            'manager_name' => ['nullable', 'string', 'max:120'],
            'manager_employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'is_active' => ['boolean'],
        ];
    }

    #[Computed]
    public function managers()
    {
        return Employee::query()
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get(['id', 'full_name', 'code']);
    }

    public function save(): void
    {
        $data = $this->validate();

        // Derive manager_name from selected active employee, or clear it
        if ($this->manager_employee_id) {
            $employee = Employee::where('status', 'active')->find($this->manager_employee_id);
            $data['manager_name'] = $employee?->full_name;
        } else {
            $data['manager_name'] = null;
        }

        if (! $this->branchId) {
            return;
        }

        $branch = Branch::find($this->branchId);

        if (! $branch) {
            return;
        }

        $branch->update($data);

        $this->editingBranch = $branch->fresh(['createdBy', 'updatedBy']);

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Branch updated',
            'message' => 'Branch updated successfully.',
        ]);
    }

    public function cancel(): void
    {
        $this->redirectRoute('general-setup.branches.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.general-setup.branches.edit', [
            'editingBranch' => $this->editingBranch,
        ])->layoutData([
            'pageTitle' => 'Edit Branch',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('branches'),
        ]);
    }
}
