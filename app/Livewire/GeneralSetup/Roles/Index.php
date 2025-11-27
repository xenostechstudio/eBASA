<?php

namespace App\Livewire\GeneralSetup\Roles;

use App\Support\GeneralSetupNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    public array $roles = [
        ['name' => 'Super Admin', 'description' => 'Full system access', 'users_count' => 2, 'color' => 'red'],
        ['name' => 'Manager', 'description' => 'Branch management and reports', 'users_count' => 5, 'color' => 'amber'],
        ['name' => 'Cashier', 'description' => 'POS and transaction access', 'users_count' => 12, 'color' => 'emerald'],
        ['name' => 'Inventory Staff', 'description' => 'Stock management', 'users_count' => 4, 'color' => 'sky'],
        ['name' => 'Viewer', 'description' => 'Read-only access', 'users_count' => 8, 'color' => 'slate'],
    ];

    public function render()
    {
        return view('livewire.general-setup.roles.index', [
            'roles' => $this->roles,
        ])->layoutData([
            'pageTitle' => 'Roles',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('roles'),
        ]);
    }
}
