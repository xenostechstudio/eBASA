<?php

namespace App\Livewire\HumanResource\Employees;

use App\Models\Employee;
use App\Support\HumanResourceNavigation;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    public Employee $employee;

    /**
     * @var array<string, mixed>
     */
    public array $form = [];

    public string $activeTab = 'personal';

    public array $tabs = [
        'personal' => 'Personal Info',
        'emergency' => 'Emergency & Banking',
    ];

    public function mount(Employee $employee): void
    {
        $this->employee = $employee;

        $this->form = [
            'full_name' => $employee->full_name,
            'preferred_name' => $employee->preferred_name,
            'code' => $employee->code,
            'email' => $employee->email,
            'phone' => $employee->phone,
            'whatsapp_number' => $employee->whatsapp_number,
            'date_of_birth' => $employee->date_of_birth?->format('Y-m-d'),
            'nik' => $employee->nik,
            'npwp' => $employee->npwp,
            'address' => $employee->address,
            'emergency_contact_name' => $employee->emergency_contact_name,
            'emergency_contact_whatsapp' => $employee->emergency_contact_whatsapp,
            'bank_name' => $employee->bank_name,
            'bank_account_number' => $employee->bank_account_number,
            'bank_account_name' => $employee->bank_account_name,
        ];
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), [], $this->validationAttributes());

        $this->employee->update($validated['form']);

        session()->flash('status', 'Employee updated successfully');

        $this->dispatch('notify', message: 'Employee updated');

        $this->redirect(route('hr.employees'), navigate: true);
    }

    public function setTab(string $tab): void
    {
        if (! array_key_exists($tab, $this->tabs)) {
            return;
        }

        $this->activeTab = $tab;
    }

    public function rules(): array
    {
        return [
            'form.full_name' => ['required', 'string', 'max:255'],
            'form.preferred_name' => ['nullable', 'string', 'max:255'],
            'form.code' => ['required', 'string', 'max:50', Rule::unique('employees', 'code')->ignore($this->employee->id)],
            'form.email' => ['required', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($this->employee->id)],
            'form.phone' => ['nullable', 'string', 'max:50'],
            'form.whatsapp_number' => ['nullable', 'string', 'max:50'],
            'form.date_of_birth' => ['nullable', 'date'],
            'form.nik' => ['required', 'string', 'max:32', Rule::unique('employees', 'nik')->ignore($this->employee->id)],
            'form.npwp' => ['nullable', 'string', 'max:32'],
            'form.address' => ['nullable', 'string'],
            'form.emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'form.emergency_contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'form.bank_name' => ['nullable', 'string', 'max:120'],
            'form.bank_account_number' => ['nullable', 'string', 'max:60'],
            'form.bank_account_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'form.full_name' => 'full name',
            'form.code' => 'employee code',
            'form.nik' => 'NIK',
            'form.npwp' => 'NPWP',
        ];
    }

    public function render()
    {
        return view('livewire.hr.employees.edit', [
            'tabs' => $this->tabs,
            'activeTab' => $this->activeTab,
        ])->layoutData([
            'pageTitle' => 'Edit Employee',
            'pageTagline' => 'HR · Employees',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links('people', 'employees'),
        ]);
    }
}
