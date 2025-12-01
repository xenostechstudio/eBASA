<?php

namespace App\Livewire\GeneralSetup\Permissions;

use App\Models\Permission;
use App\Support\GeneralSetupNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    public function render()
    {
        $permissionGroups = Permission::query()
            ->orderBy('group')
            ->orderBy('name')
            ->get()
            ->groupBy('group')
            ->map(function ($permissions, $group) {
                return [
                    'name' => ucfirst($group),
                    'permissions' => $permissions->pluck('name')->all(),
                ];
            })
            ->values()
            ->all();

        return view('livewire.general-setup.permissions.index', [
            'permissionGroups' => $permissionGroups,
        ])->layoutData([
            'pageTitle' => 'Permissions',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('permissions'),
        ]);
    }
}
