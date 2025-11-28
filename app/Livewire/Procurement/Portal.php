<?php

namespace App\Livewire\Procurement;

use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * @method $this layoutData(array $data)
 */
#[Layout('layouts.portal-sidebar')]
class Portal extends Component
{
    public function render()
    {
        $summaryCards = [
            [
                'label' => 'Active suppliers',
                'value' => 24,
                'hint' => 'across all branches',
            ],
            [
                'label' => 'Open purchase requests',
                'value' => 8,
                'hint' => 'awaiting approval',
            ],
            [
                'label' => 'Contracts expiring soon',
                'value' => 3,
                'hint' => 'next 30 days',
            ],
        ];

        $recentActivities = [
            ['title' => 'PO-2043 approved', 'subtitle' => 'BASA Mart  Tegal', 'time' => '10 minutes ago'],
            ['title' => 'New supplier onboarded', 'subtitle' => 'Fresh Dairy Co.', 'time' => '45 minutes ago'],
            ['title' => 'Contract renewal draft', 'subtitle' => 'Snack Partners', 'time' => 'Today, 10:15'],
        ];

        /** @noinspection PhpUndefinedMethodInspection */
        return view('livewire.procurement.portal', [
            'summaryCards' => $summaryCards,
            'recentActivities' => $recentActivities,
        ])->layoutData([
            'pageTitle' => 'Procurement',
            'pageTagline' => 'Suppliers & Purchasing',
            'activeModule' => 'procurement',
            'navLinks' => [],
        ]);
    }
}
