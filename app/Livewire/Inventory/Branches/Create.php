<?php

namespace App\Livewire\Inventory\Branches;

use App\Models\Branch;
use App\Support\GeneralSetupNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
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
            'is_active' => ['boolean'],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();
        Branch::create($data);

        session()->flash('status', 'Branch created successfully.');

        $this->redirectRoute('inventory.branches.index', navigate: true);
    }

    public function render(): View
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return view('livewire.inventory.branches.create')->layoutData([
            'pageTitle' => 'New Branch',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('branches'),
        ]);
    }
}
