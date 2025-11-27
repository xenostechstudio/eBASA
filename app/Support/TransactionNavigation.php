<?php

namespace App\Support;

class TransactionNavigation
{
    public static function links(string $activeSection = 'transactions'): array
    {
        return [
            [
                'label' => 'Overview',
                'children' => [
                    [
                        'label' => 'All Transactions',
                        'href' => route('transactions.index'),
                        'active' => $activeSection === 'transactions',
                    ],
                    [
                        'label' => 'Reports',
                        'href' => route('transactions.reports'),
                        'active' => $activeSection === 'reports',
                    ],
                ],
            ],
            [
                'label' => 'Records',
                'children' => [
                    [
                        'label' => 'Settlements',
                        'href' => route('transactions.settlements'),
                        'active' => $activeSection === 'settlements',
                    ],
                    [
                        'label' => 'Refunds',
                        'href' => route('transactions.refunds'),
                        'active' => $activeSection === 'refunds',
                    ],
                ],
            ],
            [
                'label' => 'Cashier',
                'children' => [
                    [
                        'label' => 'Shifts',
                        'href' => route('transactions.shifts'),
                        'active' => $activeSection === 'shifts',
                    ],
                    [
                        'label' => 'Cash Counts',
                        'href' => route('transactions.cash-counts'),
                        'active' => $activeSection === 'cash-counts',
                    ],
                ],
            ],
        ];
    }
}
