<?php

namespace App\Livewire\GeneralSetup;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal')]
class Portal extends Component
{
    public string $activeSection = 'master-data';

    protected array $sections = [
        'master-data' => 'Master Data',
        'locations' => 'Locations',
        'compliance' => 'Compliance',
        'integrations' => 'Integrations',
    ];

    public function setSection(string $section): void
    {
        if (array_key_exists($section, $this->sections)) {
            $this->activeSection = $section;
        }
    }

    public function render()
    {
        $stats = [
            ['label' => 'Branches Online', 'value' => 18, 'trend' => '+2 this quarter'],
            ['label' => 'Regions Configured', 'value' => 6, 'trend' => 'Complete'],
            ['label' => 'Shared Catalog Items', 'value' => 1_240, 'trend' => '+75 pending review'],
        ];

        $configurationAreas = [
            ['title' => 'Branch Directory', 'description' => 'Primary contact, opening hours, capabilities matrix.', 'cta' => 'Open Filament', 'status' => 'Updated 2 days ago'],
            ['title' => 'Product Families', 'description' => 'Reusable categorization powering POS and Inventory.', 'cta' => 'Review hierarchy', 'status' => 'Needs taxonomy review'],
            ['title' => 'Approval Paths', 'description' => 'Route master-data changes to merchandising/finance.', 'cta' => 'Edit flow', 'status' => '3 awaiting sign-off'],
        ];

        $locationRollout = [
            ['label' => 'Tegal', 'type' => 'Flagship', 'progress' => '100% deployed'],
            ['label' => 'Pemalang', 'type' => 'Express', 'progress' => 'Beta pilot'],
            ['label' => 'Pekalongan', 'type' => 'Warehouse', 'progress' => 'Needs configuration'],
        ];

        $complianceChecklist = [
            ['item' => 'BPOM product registry sync', 'owner' => 'QA', 'status' => 'Green'],
            ['item' => 'Halal certificate expiry tracker', 'owner' => 'Regulatory', 'status' => 'Amber'],
            ['item' => 'Supplier NDA & SLAs', 'owner' => 'Legal', 'status' => 'Red'],
        ];

        $integrationStatus = [
            ['name' => 'Inventory service', 'channel' => 'REST', 'state' => 'Healthy'],
            ['name' => 'POS catalog feed', 'channel' => 'Kafka', 'state' => 'Delayed 4 min'],
            ['name' => 'Finance ERP', 'channel' => 'SFTP nightly', 'state' => 'On schedule'],
        ];

        /** @noinspection PhpUndefinedMethodInspection */
        return view('livewire.general-setup.portal', [
            'sections' => $this->sections,
            'stats' => $stats,
            'configurationAreas' => $configurationAreas,
            'locationRollout' => $locationRollout,
            'complianceChecklist' => $complianceChecklist,
            'integrationStatus' => $integrationStatus,
        ])->layoutData([
            'pageTitle' => 'General Setup',
            'showBrand' => false,
            'navLinks' => [
                ['label' => 'Master Data', 'href' => route('general-setup.portal'), 'active' => $this->activeSection === 'master-data'],
                ['label' => 'Locations', 'href' => '#locations', 'active' => $this->activeSection === 'locations'],
                ['label' => 'Compliance', 'href' => '#compliance', 'active' => $this->activeSection === 'compliance'],
                ['label' => 'Integrations', 'href' => '#integrations', 'active' => $this->activeSection === 'integrations'],
            ],
        ]);
    }
}
