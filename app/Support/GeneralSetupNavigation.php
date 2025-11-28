<?php

namespace App\Support;

class GeneralSetupNavigation
{
    public static function links(string $activeSection = 'overview'): array
    {
        return [
            [
                'label' => 'Master Data',
                'children' => [
                    [
                        'label' => 'Users',
                        'icon' => 'heroicon-o-users',
                        'href' => route('general-setup.users.index'),
                        'active' => $activeSection === 'users',
                    ],
                    [
                        'label' => 'Branches',
                        'icon' => 'heroicon-o-building-office',
                        'href' => route('general-setup.branches.index'),
                        'active' => $activeSection === 'branches',
                    ],
                    [
                        'label' => 'Warehouses',
                        'icon' => 'heroicon-o-building-storefront',
                        'href' => route('general-setup.warehouses.index'),
                        'active' => $activeSection === 'warehouses',
                    ],
                    [
                        'label' => 'Products',
                        'icon' => 'heroicon-o-shopping-bag',
                        'href' => route('general-setup.products.index'),
                        'active' => $activeSection === 'products',
                    ],
                    [
                        'label' => 'Product Categories',
                        'icon' => 'heroicon-o-tag',
                        'href' => route('general-setup.product-categories.index'),
                        'active' => $activeSection === 'product-categories',
                    ],
                    [
                        'label' => 'Payment Methods',
                        'icon' => 'heroicon-o-credit-card',
                        'href' => route('general-setup.payment-methods.index'),
                        'active' => $activeSection === 'payment-methods',
                    ],
                ],
            ],
            [
                'label' => 'Access Control',
                'children' => [
                    [
                        'label' => 'Roles',
                        'icon' => 'heroicon-o-shield-check',
                        'href' => route('general-setup.roles.index'),
                        'active' => $activeSection === 'roles',
                    ],
                    [
                        'label' => 'Permissions',
                        'icon' => 'heroicon-o-key',
                        'href' => route('general-setup.permissions.index'),
                        'active' => $activeSection === 'permissions',
                    ],
                ],
            ],
            [
                'label' => 'System',
                'children' => [
                    [
                        'label' => 'Settings',
                        'icon' => 'heroicon-o-cog-6-tooth',
                        'href' => route('general-setup.settings.index'),
                        'active' => $activeSection === 'settings',
                    ],
                    [
                        'label' => 'Activity Logs',
                        'icon' => 'heroicon-o-clipboard-document-list',
                        'href' => route('general-setup.activity-logs.index'),
                        'active' => $activeSection === 'activity-logs',
                    ],
                ],
            ],
        ];
    }
}
