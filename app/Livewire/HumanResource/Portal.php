<?php

namespace App\Livewire\HumanResource;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Support\HumanResourceNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
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
            'employees' => Employee::count(),
            'departments' => Department::count(),
            'positions' => Position::count(),
        ];

        return view('livewire.hr.portal', [
            'sections' => $this->sections,
            'stats' => $stats,
            'peopleTabs' => $this->peopleTabs,
            'attendanceTabs' => $this->attendanceTabs,
            'payrollTabs' => $this->payrollTabs,
            'leaveTabs' => $this->leaveTabs,
            'employeeDirectoryRoute' => route('hr.employees'),
        ])->layoutData([
            'pageTitle' => 'Human Resource',
            'pageTagline' => 'People operations',
            'activeModule' => 'hr',
            'navLinks' => HumanResourceNavigation::links(
                $this->activeSection,
                $this->activeSection === 'people' ? $this->activePeopleTab : null,
            ),
        ]);
    }
}
