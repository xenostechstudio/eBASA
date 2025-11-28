<?php

namespace App\Support;

class ReportNavigation
{
    public static function links(string $activeSection = 'overview', ?string $activeChild = null): array
    {
        return [
            [
                'label' => 'Overview',
                'href' => route('reports.portal'),
                'icon' => 'heroicon-o-chart-bar-square',
                'active' => $activeSection === 'overview',
            ],
            [
                'label' => 'Sales',
                'href' => '#',
                'icon' => 'heroicon-o-currency-dollar',
                'active' => $activeSection === 'sales',
                'children' => [
                    [
                        'label' => 'Daily Sales',
                        'href' => route('reports.sales.daily'),
                        'icon' => 'heroicon-o-calendar-days',
                        'active' => $activeChild === 'daily',
                    ],
                    [
                        'label' => 'Product Sales',
                        'href' => route('reports.sales.products'),
                        'icon' => 'heroicon-o-shopping-bag',
                        'active' => $activeChild === 'products',
                    ],
                    [
                        'label' => 'Branch Performance',
                        'href' => route('reports.sales.branches'),
                        'icon' => 'heroicon-o-building-storefront',
                        'active' => $activeChild === 'branches',
                    ],
                ],
            ],
            [
                'label' => 'Inventory',
                'href' => '#',
                'icon' => 'heroicon-o-archive-box',
                'active' => $activeSection === 'inventory',
                'children' => [
                    [
                        'label' => 'Stock Summary',
                        'href' => route('reports.inventory.stock'),
                        'icon' => 'heroicon-o-cube',
                        'active' => $activeChild === 'stock',
                    ],
                    [
                        'label' => 'Movement History',
                        'href' => route('reports.inventory.movements'),
                        'icon' => 'heroicon-o-arrows-right-left',
                        'active' => $activeChild === 'movements',
                    ],
                ],
            ],
            [
                'label' => 'Financial',
                'href' => '#',
                'icon' => 'heroicon-o-banknotes',
                'active' => $activeSection === 'financial',
                'children' => [
                    [
                        'label' => 'Revenue',
                        'href' => route('reports.financial.revenue'),
                        'icon' => 'heroicon-o-chart-bar',
                        'active' => $activeChild === 'revenue',
                    ],
                    [
                        'label' => 'Expenses',
                        'href' => route('reports.financial.expenses'),
                        'icon' => 'heroicon-o-receipt-percent',
                        'active' => $activeChild === 'expenses',
                    ],
                ],
            ],
        ];
    }
}
