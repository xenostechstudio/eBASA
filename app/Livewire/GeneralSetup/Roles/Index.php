<?php

namespace App\Livewire\GeneralSetup\Roles;

use App\Models\Role;
use App\Support\GeneralSetupNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    public function setRoleColor(int $roleId, string $color): void
    {
        $allowedColors = ['red', 'amber', 'emerald', 'sky', 'slate'];

        if (! in_array($color, $allowedColors, true)) {
            return;
        }

        $role = Role::find($roleId);

        if (! $role) {
            return;
        }

        $role->color = $color;
        $role->save();
    }

    public function render()
    {
        $roles = Role::query()
            ->withCount('users')
            ->orderBy('name')
            ->get();

        return view('livewire.general-setup.roles.index', [
            'roles' => $roles,
        ])->layoutData([
            'pageTitle' => 'Roles',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('roles'),
        ]);
    }
}
