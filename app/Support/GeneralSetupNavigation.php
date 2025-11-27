<?php

namespace App\Support;

class GeneralSetupNavigation
{
    public static function links(string $activeSection = 'overview', ?string $activeChild = null): array
    {
        return [
            [
                'label' => 'Overview',
                'href' => route('general-setup.portal'),
                'active' => $activeSection === 'overview',
            ],
            [
                'label' => 'Master Data',
                'href' => '#',
                'active' => $activeSection === 'master',
                'children' => [
                    [
                        'label' => 'Retail Products',
                        'href' => route('general-setup.retail-products.index'),
                        'active' => $activeChild === 'retail-products',
                    ],
                    [
                        'label' => 'Product Categories',
                        'href' => '#product-categories',
                        'active' => $activeChild === 'product-categories',
                    ],
                ],
            ],
            [
                'label' => 'System',
                'href' => '#',
                'active' => $activeSection === 'system',
                'children' => [
                    ['label' => 'Users', 'href' => '#users'],
                    ['label' => 'Roles & Permissions', 'href' => '#roles'],
                    ['label' => 'Settings', 'href' => '#settings'],
                ],
            ],
        ];
    }
}
