<?php

namespace App\Livewire\GeneralSetup\Warehouses;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Warehouse;
use App\Support\GeneralSetupNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * @method $this layoutData(array $data)
 */
#[Layout('layouts.portal-sidebar')]
#[Title('Edit Warehouse')]
class Edit extends Component
{
    public ?int $warehouseId = null;
    public ?Warehouse $editingWarehouse = null;

    public string $code = '';
    public string $name = '';
    public ?int $branch_id = null;
    public string $city = '';
    public string $province = '';
    public string $address = '';
    public string $phone = '';
    public string $contact_name = '';
    public ?int $contact_employee_id = null;
    public bool $is_active = true;

    public function mount(Warehouse $warehouse): void
    {
        $this->warehouseId = $warehouse->id;
        $this->editingWarehouse = $warehouse->load(['branch', 'createdBy', 'updatedBy']);

        $this->code = (string) ($warehouse->code ?? '');
        $this->name = (string) ($warehouse->name ?? '');
        $this->branch_id = $warehouse->branch_id;
        $this->city = (string) ($warehouse->city ?? '');
        $this->province = (string) ($warehouse->province ?? '');
        $this->address = (string) ($warehouse->address ?? '');
        $this->phone = (string) ($warehouse->phone ?? '');
        $this->contact_name = (string) ($warehouse->contact_name ?? '');
        $this->is_active = (bool) ($warehouse->is_active ?? true);

        // Pre-select contact person based on existing contact_name when possible
        $this->contact_employee_id = null;

        if ($warehouse->contact_name) {
            $employee = Employee::query()
                ->where('status', 'active')
                ->where('full_name', $warehouse->contact_name)
                ->first();

            if ($employee) {
                $this->contact_employee_id = $employee->id;
            }
        }
    }

    protected function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('warehouses', 'code')->ignore($this->warehouseId)],
            'name' => ['required', 'string', 'max:120'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'city' => ['nullable', 'string', 'max:120'],
            'province' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'contact_name' => ['nullable', 'string', 'max:120'],
            'contact_employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'is_active' => ['boolean'],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        // Derive contact_name from selected active employee if provided
        if ($this->contact_employee_id) {
            $employee = Employee::query()
                ->where('status', 'active')
                ->find($this->contact_employee_id);

            $data['contact_name'] = $employee?->full_name ?? $this->contact_name ?: null;
        } else {
            $data['contact_name'] = $this->contact_name ?: null;
        }

        if (! $this->warehouseId) {
            return;
        }

        $warehouse = Warehouse::find($this->warehouseId);

        if (! $warehouse) {
            return;
        }

        $warehouse->update($data);

        $this->editingWarehouse = $warehouse->fresh(['branch', 'createdBy', 'updatedBy']);

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Warehouse updated',
            'message' => 'Warehouse updated successfully.',
        ]);
    }

    public function cancel(): void
    {
        $this->redirectRoute('general-setup.warehouses.index', navigate: true);
    }

    #[Computed]
    public function employees()
    {
        return Employee::query()
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get(['id', 'full_name', 'code']);
    }

    public function render(): View
    {
        $branches = Branch::orderBy('name')->get(['id', 'name', 'code']);

        return view('livewire.general-setup.warehouses.edit', [
            'editingWarehouse' => $this->editingWarehouse,
            'branches' => $branches,
        ])->layoutData([
            'pageTitle' => 'Edit Warehouse',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('warehouses'),
        ]);
    }
}
