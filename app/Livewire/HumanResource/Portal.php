<?php

namespace App\Livewire\HumanResource;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal')]
class Portal extends Component
{
    public string $activeSection = 'overview';
    public string $activePeopleTab = 'employees';
    public string $activeAttendanceTab = 'timesheets';
    public string $activePayrollTab = 'runs';
    public string $activeLeaveTab = 'requests';

    protected array $sections = [
        'overview' => 'Overview',
        'people' => 'People & Teams',
        'attendance' => 'Attendance',
        'payroll' => 'Payroll',
        'leave' => 'Leave Management',
    ];

    protected array $peopleTabs = [
        'employees' => 'Employees',
        'teams' => 'Teams',
        'org' => 'Org Chart',
    ];

    protected array $attendanceTabs = [
        'timesheets' => 'Timesheets',
        'exceptions' => 'Exceptions',
        'compliance' => 'Compliance',
    ];

    protected array $payrollTabs = [
        'runs' => 'Payroll Runs',
        'adjustments' => 'Adjustments',
        'payouts' => 'Payouts',
    ];

    protected array $leaveTabs = [
        'requests' => 'Requests',
        'types' => 'Leave Types',
        'policies' => 'Policies',
    ];

    public function setSection(string $section): void
    {
        if (array_key_exists($section, $this->sections)) {
            $this->activeSection = $section;

            if ($section !== 'people') {
                $this->activePeopleTab = 'employees';
            }

            if ($section !== 'attendance') {
                $this->activeAttendanceTab = 'timesheets';
            }

            if ($section !== 'payroll') {
                $this->activePayrollTab = 'runs';
            }

            if ($section !== 'leave') {
                $this->activeLeaveTab = 'requests';
            }
        }
    }

    public function setPeopleTab(string $tab): void
    {
        if (array_key_exists($tab, $this->peopleTabs)) {
            $this->activePeopleTab = $tab;
        }
    }

    public function setAttendanceTab(string $tab): void
    {
        if (array_key_exists($tab, $this->attendanceTabs)) {
            $this->activeAttendanceTab = $tab;
        }
    }

    public function setPayrollTab(string $tab): void
    {
        if (array_key_exists($tab, $this->payrollTabs)) {
            $this->activePayrollTab = $tab;
        }
    }

    public function setLeaveTab(string $tab): void
    {
        if (array_key_exists($tab, $this->leaveTabs)) {
            $this->activeLeaveTab = $tab;
        }
    }

    public function render()
    {
        $stats = [
            [
                'label' => 'Active Employees',
                'value' => 182,
                'trend' => '+6 this month',
            ],
            [
                'label' => 'Open Positions',
                'value' => 12,
                'trend' => '4 urgent',
            ],
            [
                'label' => 'Attendance Compliance',
                'value' => '97%',
                'trend' => '+2% vs last month',
            ],
        ];

        $upcomingReviews = [
            ['name' => 'Aria Saputra', 'role' => 'Store Manager', 'date' => 'Nov 25'],
            ['name' => 'Dwi Haryanto', 'role' => 'Senior Buyer', 'date' => 'Nov 28'],
            ['name' => 'Maya Rahma', 'role' => 'HRBP Jakarta', 'date' => 'Dec 02'],
        ];

        $recentHires = [
            ['name' => 'Gilang Pratama', 'role' => 'Warehouse Lead', 'branch' => 'Bandung'],
            ['name' => 'Salsabila Putri', 'role' => 'Finance Analyst', 'branch' => 'HQ'],
        ];

        /** @noinspection PhpUndefinedMethodInspection */
        return view('livewire.hr.portal', [
            'sections' => $this->sections,
            'stats' => $stats,
            'upcomingReviews' => $upcomingReviews,
            'recentHires' => $recentHires,
            'peopleTabs' => $this->peopleTabs,
            'attendanceTabs' => $this->attendanceTabs,
            'payrollTabs' => $this->payrollTabs,
            'leaveTabs' => $this->leaveTabs,
            'employeeDirectoryRoute' => route('hr.employees'),
        ])->layoutData([
            'pageTitle' => 'Human Resource',
            'showBrand' => false,
            'navLinks' => [
                ['label' => 'Overview', 'href' => route('hr.portal'), 'active' => $this->activeSection === 'overview'],
                [
                    'label' => 'People',
                    'href' => '#',
                    'active' => $this->activeSection === 'people',
                    'children' => [
                        ['label' => 'Employees', 'href' => route('hr.employees')],
                        ['label' => 'Employments', 'href' => route('hr.employments')],
                        ['label' => 'Departments', 'href' => '#departments'],
                        ['label' => 'Positions', 'href' => '#positions'],
                    ],
                ],
                [
                    'label' => 'Attendance',
                    'href' => '#',
                    'active' => $this->activeSection === 'attendance',
                    'children' => [
                        ['label' => 'Timesheets', 'href' => '#timesheets'],
                        ['label' => 'Exceptions', 'href' => '#exceptions'],
                        ['label' => 'Compliance', 'href' => '#compliance'],
                    ],
                ],
                [
                    'label' => 'Payroll',
                    'href' => '#',
                    'active' => $this->activeSection === 'payroll',
                    'children' => [
                        ['label' => 'Payroll Runs', 'href' => '#runs'],
                        ['label' => 'Adjustments', 'href' => '#adjustments'],
                        ['label' => 'Payouts', 'href' => '#payouts'],
                    ],
                ],
                [
                    'label' => 'Leave Management',
                    'href' => '#',
                    'active' => $this->activeSection === 'leave',
                    'children' => [
                        ['label' => 'Leave Requests', 'href' => '#leave-requests'],
                        ['label' => 'Leave Types', 'href' => '#leave-types'],
                        ['label' => 'Policies & Approvals', 'href' => '#leave-policies'],
                    ],
                ],
            ],
        ]);
    }
}
