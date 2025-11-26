<?php

namespace App\Livewire\HumanResource\Employees;

use App\Models\Employee;
use App\Support\HumanResourceNavigation;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal')]
class Create extends Component
{
    /**
     * @var array<string, mixed>
     */
    public array $form = [
        'full_name' => '',
        'preferred_name' => '',
        'code' => '',
        'email' => '',
        'phone' => '',
        'whatsapp_number' => '',
        'date_of_birth' => null,
        'nik' => '',
        'npwp' => '',
        'address' => '',
        'emergency_contact_name' => '',
        'emergency_contact_whatsapp' => '',
        'bank_name' => '',
        'bank_account_number' => '',
        'bank_account_name' => '',
    ];

    public string $activeTab = 'personal';

    public array $tabs = [
        'personal' => 'Personal Info',
        'emergency' => 'Emergency Contact',
        'banking' => 'Banking Details',
        'review' => 'Review',
    ];

    /**
     * @var array<string, string>
     */
    protected array $fieldTabMap = [
        // Personal
        'form.full_name' => 'personal',
        'form.preferred_name' => 'personal',
        'form.code' => 'personal',
        'form.email' => 'personal',
        'form.phone' => 'personal',
        'form.whatsapp_number' => 'personal',
        'form.date_of_birth' => 'personal',
        'form.nik' => 'personal',
        'form.npwp' => 'personal',
        'form.address' => 'personal',

        // Emergency
        'form.emergency_contact_name' => 'emergency',
        'form.emergency_contact_whatsapp' => 'emergency',

        // Banking
        'form.bank_name' => 'banking',
        'form.bank_account_number' => 'banking',
        'form.bank_account_name' => 'banking',
    ];

    public function setTab(string $tab): void
    {
        if (! array_key_exists($tab, $this->tabs)) {
            return;
        }

        $this->activeTab = $tab;
    }

    public function goToNextTab(): void
    {
        $keys = array_keys($this->tabs);
        $index = $this->tabIndex($this->activeTab);

        if ($index === null || $index >= count($keys) - 1) {
            return;
        }

        $this->setTab($keys[$index + 1]);
    }

    public function goToPreviousTab(): void
    {
        $keys = array_keys($this->tabs);
        $index = $this->tabIndex($this->activeTab);

        if ($index === null || $index === 0) {
            return;
        }

        $this->activeTab = $keys[$index - 1];
    }

    protected function tabIndex(?string $tab): ?int
    {
        if ($tab === null) {
            return null;
        }

        $keys = array_keys($this->tabs);
        $index = array_search($tab, $keys, true);

        return $index === false ? null : $index;
    }


    public function save(): void
    {
        try {
            $validated = $this->validate($this->rules(), [], $this->validationAttributes());
        } catch (ValidationException $exception) {
            $errors = $exception->validator->errors()->toArray();
            $firstField = array_key_first($errors);

            if ($firstField && isset($this->fieldTabMap[$firstField])) {
                $this->activeTab = $this->fieldTabMap[$firstField];
            }

            throw $exception;
        }

        Employee::create($validated['form']);

        session()->flash('status', 'Employee created successfully');

        $this->dispatch('notify', message: 'Employee created');

        $this->redirect(route('hr.employees'), navigate: true);
    }

    public function saveDraft(): void
    {
        // Save partial data for later completion
        $this->dispatch('notify', message: 'Draft saved (feature coming soon)');
    }

    public function rules(): array
    {
        return [
            'form.full_name' => ['required', 'string', 'max:255'],
            'form.preferred_name' => ['nullable', 'string', 'max:255'],
            'form.code' => ['required', 'string', 'max:50', Rule::unique('employees', 'code')],
            'form.email' => ['required', 'email', 'max:255', Rule::unique('employees', 'email')],
            'form.phone' => ['nullable', 'string', 'max:50'],
            'form.whatsapp_number' => ['nullable', 'string', 'max:50'],
            'form.date_of_birth' => ['nullable', 'date'],
            'form.nik' => ['required', 'string', 'max:32', Rule::unique('employees', 'nik')],
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
        return view('livewire.hr.employees.create', [
            'tabs' => $this->tabs,
            'activeTab' => $this->activeTab,
        ])->layoutData([
            'pageTitle' => 'Human Resource',
            'showBrand' => false,
            'navLinks' => HumanResourceNavigation::links('people', 'employees'),
        ]);
    }
}
