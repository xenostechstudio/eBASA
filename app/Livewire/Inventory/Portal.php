<?php

namespace App\Livewire\Inventory;

use App\Support\InventoryNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * @method $this layoutData(array $data)
 */
#[Layout('layouts.portal-sidebar')]
class Portal extends Component
{
    public array $sections = [
        'overview' => 'Overview',
        'branches' => 'Branches',
        'stock' => 'Stock Visibility',
        'catalog' => 'Catalog',
        'procurement' => 'Procurement',
    ];

    public string $activeSection = 'overview';

    public function setSection(string $section): void
    {
        if (array_key_exists($section, $this->sections)) {
            $this->activeSection = $section;
        }
    }

    public function render()
    {
        $summaryCards = [
            [
                'label' => 'Branches Monitored',
                'value' => '12',
                'trend' => '3 new this quarter',
            ],
            [
                'label' => 'SKUs in Catalog',
                'value' => '4,583',
                'trend' => '+215 vs last month',
            ],
            [
                'label' => 'Stock Health',
                'value' => '89%',
                'trend' => 'Ideal range 88-92%',
            ],
        ];

        $recentActivities = [
            ['title' => 'Penerimaan barang Gudang Tegal', 'timestamp' => '10 minutes ago', 'type' => 'Inbound'],
            ['title' => 'Stock adjustment Pekalongan', 'timestamp' => '45 minutes ago', 'type' => 'Adjustment'],
            ['title' => 'Transfer Pemalang → Tegal', 'timestamp' => 'Today, 10:15', 'type' => 'Transfer'],
        ];

        $branchHealth = [
            ['name' => 'Tegal', 'status' => 'stable', 'fill' => 92],
            ['name' => 'Pemalang', 'status' => 'watch', 'fill' => 78],
            ['name' => 'Pekalongan', 'status' => 'stable', 'fill' => 88],
        ];

        /** @noinspection PhpUndefinedMethodInspection */
        return view('livewire.inventory.portal', [
            'summaryCards' => $summaryCards,
            'recentActivities' => $recentActivities,
            'branchHealth' => $branchHealth,
            'sections' => $this->sections,
        ])->layoutData([
            'pageTitle' => 'Inventory',
            'pageTagline' => 'Stock & Operations',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('overview'),
        ]);
    }
}
