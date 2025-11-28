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
                        'icon' => 'heroicon-o-queue-list',
                        'href' => route('transactions.index'),
                        'active' => $activeSection === 'transactions',
                    ],
                    [
                        'label' => 'Reports',
                        'icon' => 'heroicon-o-chart-bar',
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
                        'icon' => 'heroicon-o-banknotes',
                        'href' => route('transactions.settlements'),
                        'active' => $activeSection === 'settlements',
                    ],
                    [
                        'label' => 'Refunds',
                        'icon' => 'heroicon-o-arrow-uturn-left',
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
                        'icon' => 'heroicon-o-clock',
                        'href' => route('transactions.shifts'),
                        'active' => $activeSection === 'shifts',
                    ],
                    [
                        'label' => 'Cash Counts',
                        'icon' => 'heroicon-o-calculator',
                        'href' => route('transactions.cash-counts'),
                        'active' => $activeSection === 'cash-counts',
                    ],
                ],
            ],
        ];
    }
}
