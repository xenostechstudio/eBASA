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
                'active' => $activeSection === 'overview',
            ],
            [
                'label' => 'People',
                'href' => '#',
                'active' => $activeSection === 'people',
                'children' => [
                    [
                        'label' => 'Employees',
                        'href' => route('hr.employees'),
                        'active' => $activePeopleChild === 'employees',
                    ],
                    [
                        'label' => 'Employments',
                        'href' => route('hr.employments'),
                        'active' => $activePeopleChild === 'employments',
                    ],
                    [
                        'label' => 'Departments',
                        'href' => route('hr.departments'),
                    ],
                    [
                        'label' => 'Positions',
                        'href' => route('hr.positions'),
                    ],
                ],
            ],
            [
                'label' => 'Attendance',
                'href' => '#',
                'active' => $activeSection === 'attendance',
                'children' => [
                    ['label' => 'Timesheets', 'href' => '#timesheets'],
                    ['label' => 'Exceptions', 'href' => '#exceptions'],
                    ['label' => 'Compliance', 'href' => '#compliance'],
                ],
            ],
            [
                'label' => 'Payroll',
                'href' => '#',
                'active' => $activeSection === 'payroll',
                'children' => [
                    ['label' => 'Payroll Runs', 'href' => '#runs'],
                    ['label' => 'Adjustments', 'href' => '#adjustments'],
                    ['label' => 'Payouts', 'href' => '#payouts'],
                ],
            ],
            [
                'label' => 'Leave Management',
                'href' => '#',
                'active' => $activeSection === 'leave',
                'children' => [
                    ['label' => 'Leave Requests', 'href' => '#leave-requests'],
                    ['label' => 'Leave Types', 'href' => '#leave-types'],
                    ['label' => 'Policies & Approvals', 'href' => '#leave-policies'],
                ],
            ],
        ];
    }
}
