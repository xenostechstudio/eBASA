<?php

namespace App\Livewire\GeneralSetup\Roles;

use App\Models\Permission;
use App\Models\Role;
use App\Support\GeneralSetupNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * @method $this layoutData(array $data)
 */
#[Layout('layouts.portal-sidebar')]
#[Title('Role Permissions')]
class Permissions extends Component
{
    public Role $role;

    /**
     * @var array<int>
     */
    public array $selectedPermissions = [];

    public function mount(Role $role): void
    {
        $this->role = $role;
        $this->selectedPermissions = $role->permissions()->pluck('permissions.id')->all();
    }

    protected function rules(): array
    {
        return [
            'selectedPermissions' => ['array'],
            'selectedPermissions.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->role->permissions()->sync($this->selectedPermissions);

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Permissions updated',
            'message' => 'Role permissions updated successfully.',
        ]);

        $this->redirectRoute('general-setup.roles.index', navigate: true);
    }

    public function cancel(): void
    {
        $this->redirectRoute('general-setup.roles.index', navigate: true);
    }

    public function render(): View
    {
        $permissionGroups = Permission::query()
            ->orderBy('group')
            ->orderBy('name')
            ->get()
            ->groupBy('group')
            ->map(function ($permissions, $group) {
                return [
                    'key' => $group,
                    'name' => ucfirst($group),
                    'permissions' => $permissions,
                ];
            })
            ->values();

        return view('livewire.general-setup.roles.permissions', [
            'role' => $this->role,
            'permissionGroups' => $permissionGroups,
        ])->layoutData([
            'pageTitle' => 'Role Permissions',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('roles'),
        ]);
    }
}
