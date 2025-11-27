<?php

namespace App\Livewire\GeneralSetup\Permissions;

use App\Support\GeneralSetupNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    public array $permissionGroups = [
        [
            'name' => 'Users',
            'permissions' => ['view users', 'create users', 'edit users', 'delete users'],
        ],
        [
            'name' => 'Products',
            'permissions' => ['view products', 'create products', 'edit products', 'delete products'],
        ],
        [
            'name' => 'Transactions',
            'permissions' => ['view transactions', 'create transactions', 'refund transactions', 'export transactions'],
        ],
        [
            'name' => 'Inventory',
            'permissions' => ['view inventory', 'adjust stock', 'transfer stock', 'manage branches'],
        ],
        [
            'name' => 'Reports',
            'permissions' => ['view reports', 'export reports', 'view analytics'],
        ],
    ];

    public function render()
    {
        return view('livewire.general-setup.permissions.index', [
            'permissionGroups' => $this->permissionGroups,
        ])->layoutData([
            'pageTitle' => 'Permissions',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('permissions'),
        ]);
    }
}
