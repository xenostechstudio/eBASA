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
                'icon' => 'heroicon-o-cube',
                'active' => $activeSection === 'overview',
            ],
            [
                'label' => 'Stock',
                'href' => '#',
                'icon' => 'heroicon-o-archive-box',
                'active' => $activeSection === 'stock',
                'children' => [
                    [
                        'label' => 'Stock Levels',
                        'href' => route('inventory.stock.levels'),
                        'icon' => 'heroicon-o-chart-bar',
                        'active' => $activeChild === 'levels',
                    ],
                    [
                        'label' => 'Adjustments',
                        'href' => route('inventory.stock.adjustments'),
                        'icon' => 'heroicon-o-adjustments-horizontal',
                        'active' => $activeChild === 'adjustments',
                    ],
                    [
                        'label' => 'Transfers',
                        'href' => route('inventory.stock.transfers'),
                        'icon' => 'heroicon-o-arrows-right-left',
                        'active' => $activeChild === 'transfers',
                    ],
                ],
            ],
            [
                'label' => 'Catalog',
                'href' => '#',
                'icon' => 'heroicon-o-rectangle-stack',
                'active' => $activeSection === 'catalog',
                'children' => [
                    [
                        'label' => 'Products',
                        'href' => route('inventory.catalog.products'),
                        'icon' => 'heroicon-o-shopping-bag',
                        'active' => $activeChild === 'products',
                    ],
                    [
                        'label' => 'Bundles',
                        'href' => route('inventory.catalog.bundles'),
                        'icon' => 'heroicon-o-squares-2x2',
                        'active' => $activeChild === 'bundles',
                    ],
                    [
                        'label' => 'Price Lists',
                        'href' => route('inventory.catalog.price-lists'),
                        'icon' => 'heroicon-o-currency-dollar',
                        'active' => $activeChild === 'price-lists',
                    ],
                ],
            ],
        ];
    }
}
