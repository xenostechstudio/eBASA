<?php

namespace App\Livewire\GeneralSetup;

use App\Models\RetailProduct;
use App\Models\RetailProductCategory;
use App\Models\User;
use App\Support\GeneralSetupNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Portal extends Component
{
    public function render()
    {
        $stats = [
            'users' => User::count(),
            'products' => RetailProduct::count(),
            'categories' => RetailProductCategory::count(),
        ];

        return view('livewire.general-setup.portal', [
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'General Setup',
            'pageTagline' => 'System Configuration',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('overview'),
        ]);
    }
}
