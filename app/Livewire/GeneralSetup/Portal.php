<?php

namespace App\Livewire\GeneralSetup;

use App\Models\Product;
use App\Models\ProductCategory;
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
            'products' => Product::count(),
            'categories' => ProductCategory::count(),
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
