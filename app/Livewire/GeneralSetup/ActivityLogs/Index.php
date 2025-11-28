<?php

namespace App\Livewire\GeneralSetup\ActivityLogs;

use App\Support\GeneralSetupNavigation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    public array $logs = [
        ['user' => 'Admin', 'action' => 'Created user "John Doe"', 'module' => 'Users', 'time' => '2 minutes ago', 'type' => 'create'],
        ['user' => 'Manager', 'action' => 'Updated product "Coffee Latte"', 'module' => 'Products', 'time' => '15 minutes ago', 'type' => 'update'],
        ['user' => 'Cashier', 'action' => 'Processed refund #TRX-001234', 'module' => 'Transactions', 'time' => '1 hour ago', 'type' => 'refund'],
        ['user' => 'Admin', 'action' => 'Deleted category "Discontinued"', 'module' => 'Categories', 'time' => '2 hours ago', 'type' => 'delete'],
        ['user' => 'System', 'action' => 'Backup completed successfully', 'module' => 'System', 'time' => '3 hours ago', 'type' => 'system'],
    ];

    public function export(string $format): void
    {
        if (! in_array($format, ['excel', 'pdf'], true)) {
            return;
        }

        $label = $format === 'excel' ? 'CSV' : strtoupper($format);

        session()->flash('flash', [
            'type' => 'info',
            'title' => $label . ' export',
            'message' => 'Export functionality is not implemented yet.',
        ]);
    }

    public function render()
    {
        return view('livewire.general-setup.activity-logs.index', [
            'logs' => $this->logs,
        ])->layoutData([
            'pageTitle' => 'Activity Logs',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('activity-logs'),
        ]);
    }
}
