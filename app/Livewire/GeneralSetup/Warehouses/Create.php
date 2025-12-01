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
#[Title('Add Warehouse')]
class Create extends Component
{
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

    protected function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('warehouses', 'code')],
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

        $warehouse = Warehouse::create($data);

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Warehouse created',
            'message' => 'Warehouse created successfully.',
        ]);

        $this->redirectRoute('general-setup.warehouses.edit', ['warehouse' => $warehouse->id], navigate: true);
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

        return view('livewire.general-setup.warehouses.create', [
            'branches' => $branches,
        ])->layoutData([
            'pageTitle' => 'Add Warehouse',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('warehouses'),
        ]);
    }
}
