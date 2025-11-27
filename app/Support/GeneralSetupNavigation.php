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
                        'href' => route('general-setup.users.index'),
                        'active' => $activeSection === 'users',
                    ],
                    [
                        'label' => 'Retail Products',
                        'href' => route('general-setup.retail-products.index'),
                        'active' => $activeSection === 'retail-products',
                    ],
                    [
                        'label' => 'Product Categories',
                        'href' => route('general-setup.product-categories.index'),
                        'active' => $activeSection === 'product-categories',
                    ],
                    [
                        'label' => 'Payment Methods',
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
                        'href' => route('general-setup.roles.index'),
                        'active' => $activeSection === 'roles',
                    ],
                    [
                        'label' => 'Permissions',
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
                        'href' => route('general-setup.settings.index'),
                        'active' => $activeSection === 'settings',
                    ],
                    [
                        'label' => 'Activity Logs',
                        'href' => route('general-setup.activity-logs.index'),
                        'active' => $activeSection === 'activity-logs',
                    ],
                ],
            ],
        ];
    }
}
