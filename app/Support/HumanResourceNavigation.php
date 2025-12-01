<?php

namespace App\Support;

class HumanResourceNavigation
{
    public static function links(string $activeSection = 'overview', ?string $activeChild = null): array
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
                        'active' => $activeChild === 'employees',
                    ],
                    [
                        'label' => 'Employments',
                        'href' => route('hr.employments'),
                        'icon' => 'heroicon-o-briefcase',
                        'active' => $activeChild === 'employments',
                    ],
                    [
                        'label' => 'Departments',
                        'href' => route('hr.departments'),
                        'icon' => 'heroicon-o-building-office-2',
                        'active' => $activeChild === 'departments',
                    ],
                    [
                        'label' => 'Positions',
                        'href' => route('hr.positions'),
                        'icon' => 'heroicon-o-rectangle-group',
                        'active' => $activeChild === 'positions',
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
                        'label' => 'Daily Attendance',
                        'href' => route('hr.attendances'),
                        'icon' => 'heroicon-o-calendar-days',
                        'active' => $activeChild === 'attendances',
                    ],
                    [
                        'label' => 'Shifts',
                        'href' => route('hr.shifts'),
                        'icon' => 'heroicon-o-clock',
                        'active' => $activeChild === 'shifts',
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
                        'label' => 'Payroll Items',
                        'href' => route('hr.payroll-items'),
                        'icon' => 'heroicon-o-queue-list',
                        'active' => $activeChild === 'payroll-items',
                    ],
                    [
                        'label' => 'Payroll Groups',
                        'href' => route('hr.payroll-groups'),
                        'icon' => 'heroicon-o-rectangle-stack',
                        'active' => $activeChild === 'payroll-groups',
                    ],
                    [
                        'label' => 'Payroll Runs',
                        'href' => route('hr.payroll-runs'),
                        'icon' => 'heroicon-o-chart-bar',
                        'active' => $activeChild === 'payroll-runs',
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
                        'href' => route('hr.leave-requests'),
                        'icon' => 'heroicon-o-envelope-open',
                        'active' => $activeChild === 'leave-requests',
                    ],
                    [
                        'label' => 'Leave Types',
                        'href' => route('hr.leave-types'),
                        'icon' => 'heroicon-o-squares-2x2',
                        'active' => $activeChild === 'leave-types',
                    ],
                ],
            ],
        ];
    }
}
