<?php

namespace App\Support;

class InventoryNavigation
{
    public static function links(string $activeSection = 'overview', ?string $activeChild = null): array
    {
        return [
            [
                'label' => 'Overview',
                'href' => route('inventory.portal'),
                'active' => $activeSection === 'overview',
            ],
            [
                'label' => 'Branches',
                'href' => route('inventory.branches.index'),
                'active' => $activeSection === 'branches',
                'children' => [
                    [
                        'label' => 'Directory',
                        'href' => route('inventory.branches.index'),
                        'active' => $activeChild === 'branches.index',
                    ],
                    [
                        'label' => 'Create branch',
                        'href' => route('inventory.branches.create'),
                        'active' => $activeChild === 'branches.create',
                    ],
                ],
            ],
            [
                'label' => 'Stock',
                'href' => '#',
                'active' => $activeSection === 'stock',
                'children' => [
                    ['label' => 'Levels', 'href' => '#stock-levels'],
                    ['label' => 'Adjustments', 'href' => '#stock-adjustments'],
                ],
            ],
            [
                'label' => 'Catalog',
                'href' => '#',
                'active' => $activeSection === 'catalog',
                'children' => [
                    ['label' => 'Products', 'href' => '#catalog-products'],
                    ['label' => 'Bundles', 'href' => '#catalog-bundles'],
                    ['label' => 'Price lists', 'href' => '#catalog-pricing'],
                ],
            ],
            [
                'label' => 'Procurement',
                'href' => '#',
                'active' => $activeSection === 'procurement',
                'children' => [
                    ['label' => 'Suppliers', 'href' => '#procurement-suppliers'],
                    ['label' => 'Purchase requests', 'href' => '#procurement-requests'],
                ],
            ],
        ];
    }
}
