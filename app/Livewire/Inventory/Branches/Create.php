<?php

namespace App\Livewire\Inventory\Branches;

use App\Models\Branch;
use App\Models\Employee;
use App\Support\GeneralSetupNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * @method $this layoutData(array $data)
 */
#[Layout('layouts.portal-sidebar')]
class Create extends Component
{
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

    protected function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('branches', 'code')],
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

        Branch::create($data);

        session()->flash('status', 'Branch created successfully.');

        $this->redirectRoute('general-setup.branches.index', navigate: true);
    }

    public function render(): View
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return view('livewire.general-setup.branches.create')->layoutData([
            'pageTitle' => 'New Branch',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('branches'),
        ]);
    }
}
