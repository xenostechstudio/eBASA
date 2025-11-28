<?php

namespace App\Support;

class HumanResourceNavigation
{
    public static function links(string $activeSection = 'overview', ?string $activePeopleChild = null): array
    {
        return [
            [
                'label' => 'Overview',
                'href' => route('hr.portal'),
                'icon' => 'heroicon-o-user-group',
                'active' => $activeSection === 'overview',
            ],
            [
                'label' => 'People',
                'href' => '#',
                'icon' => 'heroicon-o-identification',
                'active' => $activeSection === 'people',
                'children' => [
                    [
                        'label' => 'Employees',
                        'href' => route('hr.employees'),
                        'icon' => 'heroicon-o-user-group',
                        'active' => $activePeopleChild === 'employees',
                    ],
                    [
                        'label' => 'Employments',
                        'href' => route('hr.employments'),
                        'icon' => 'heroicon-o-briefcase',
                        'active' => $activePeopleChild === 'employments',
                    ],
                    [
                        'label' => 'Departments',
                        'href' => route('hr.departments'),
                        'icon' => 'heroicon-o-building-office-2',
                    ],
                    [
                        'label' => 'Positions',
                        'href' => route('hr.positions'),
                        'icon' => 'heroicon-o-rectangle-group',
                    ],
                ],
            ],
            [
                'label' => 'Attendance',
                'href' => '#',
                'icon' => 'heroicon-o-clock',
                'active' => $activeSection === 'attendance',
                'children' => [
                    [
                        'label' => 'Timesheets',
                        'href' => '#timesheets',
                        'icon' => 'heroicon-o-calendar-days',
                    ],
                    [
                        'label' => 'Exceptions',
                        'href' => '#exceptions',
                        'icon' => 'heroicon-o-exclamation-circle',
                    ],
                    [
                        'label' => 'Compliance',
                        'href' => '#compliance',
                        'icon' => 'heroicon-o-shield-check',
                    ],
                ],
            ],
            [
                'label' => 'Payroll',
                'href' => '#',
                'icon' => 'heroicon-o-banknotes',
                'active' => $activeSection === 'payroll',
                'children' => [
                    [
                        'label' => 'Payroll Runs',
                        'href' => '#runs',
                        'icon' => 'heroicon-o-chart-bar',
                    ],
                    [
                        'label' => 'Adjustments',
                        'href' => '#adjustments',
                        'icon' => 'heroicon-o-adjustments-horizontal',
                    ],
                    [
                        'label' => 'Payouts',
                        'href' => '#payouts',
                        'icon' => 'heroicon-o-receipt-percent',
                    ],
                ],
            ],
            [
                'label' => 'Leave Management',
                'href' => '#',
                'icon' => 'heroicon-o-clipboard-document-list',
                'active' => $activeSection === 'leave',
                'children' => [
                    [
                        'label' => 'Leave Requests',
                        'href' => '#leave-requests',
                        'icon' => 'heroicon-o-envelope-open',
                    ],
                    [
                        'label' => 'Leave Types',
                        'href' => '#leave-types',
                        'icon' => 'heroicon-o-squares-2x2',
                    ],
                    [
                        'label' => 'Policies & Approvals',
                        'href' => '#leave-policies',
                        'icon' => 'heroicon-o-scale',
                    ],
                ],
            ],
        ];
    }
}
