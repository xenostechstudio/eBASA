<?php

namespace App\Livewire\GeneralSetup\Settings;

use App\Support\GeneralSetupNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    public array $settingGroups = [
        [
            'name' => 'Company',
            'icon' => 'heroicon-o-building-office',
            'settings' => [
                ['key' => 'company_name', 'label' => 'Company Name', 'value' => 'BASA Retail'],
                ['key' => 'company_address', 'label' => 'Address', 'value' => 'Jl. Example No. 123'],
                ['key' => 'company_phone', 'label' => 'Phone', 'value' => '+62 123 456 789'],
            ],
        ],
        [
            'name' => 'POS',
            'icon' => 'heroicon-o-shopping-cart',
            'settings' => [
                ['key' => 'receipt_header', 'label' => 'Receipt Header', 'value' => 'Thank you for shopping!'],
                ['key' => 'receipt_footer', 'label' => 'Receipt Footer', 'value' => 'Visit us again!'],
                ['key' => 'auto_print_receipt', 'label' => 'Auto Print Receipt', 'value' => 'Yes'],
            ],
        ],
        [
            'name' => 'Tax',
            'icon' => 'heroicon-o-calculator',
            'settings' => [
                ['key' => 'tax_rate', 'label' => 'Default Tax Rate', 'value' => '11%'],
                ['key' => 'tax_inclusive', 'label' => 'Tax Inclusive Pricing', 'value' => 'Yes'],
            ],
        ],
    ];

    public function render()
    {
        return view('livewire.general-setup.settings.index', [
            'settingGroups' => $this->settingGroups,
        ])->layoutData([
            'pageTitle' => 'Settings',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('settings'),
        ]);
    }
}
