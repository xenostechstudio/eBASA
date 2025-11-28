<?php

namespace App\Support;

class ProcurementNavigation
{
    public static function links(string $activeSection = 'overview', ?string $activeChild = null): array
    {
        return [
            [
                'label' => 'Overview',
                'href' => route('procurement.portal'),
                'icon' => 'heroicon-o-clipboard-document-list',
                'active' => $activeSection === 'overview',
            ],
            [
                'label' => 'Suppliers',
                'href' => route('procurement.suppliers'),
                'icon' => 'heroicon-o-building-office',
                'active' => $activeSection === 'suppliers',
            ],
            [
                'label' => 'Purchase Orders',
                'href' => '#',
                'icon' => 'heroicon-o-document-text',
                'active' => $activeSection === 'orders',
                'children' => [
                    [
                        'label' => 'All Orders',
                        'href' => route('procurement.orders'),
                        'icon' => 'heroicon-o-queue-list',
                        'active' => $activeChild === 'all',
                    ],
                    [
                        'label' => 'Create Order',
                        'href' => route('procurement.orders.create'),
                        'icon' => 'heroicon-o-plus-circle',
                        'active' => $activeChild === 'create',
                    ],
                ],
            ],
            [
                'label' => 'Goods Receipt',
                'href' => route('procurement.receipts'),
                'icon' => 'heroicon-o-inbox-arrow-down',
                'active' => $activeSection === 'receipts',
            ],
            [
                'label' => 'Returns',
                'href' => route('procurement.returns'),
                'icon' => 'heroicon-o-arrow-uturn-left',
                'active' => $activeSection === 'returns',
            ],
        ];
    }
}
