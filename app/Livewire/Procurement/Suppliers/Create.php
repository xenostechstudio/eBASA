<?php

namespace App\Livewire\Procurement\Suppliers;

use App\Support\ProcurementNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Create extends Component
{
    /**
     * @var array<string, mixed>
     */
    public array $form = [
        'name' => '',
        'code' => '',
        'contact_name' => '',
        'email' => '',
        'phone' => '',
        'tax_number' => '',
        'address' => '',
        'payment_terms' => '30',
        'is_active' => true,
        'notes' => '',
    ];

    public function save(): void
    {
        // In a real implementation this would persist to the database.
        $this->validate([
            'form.name' => ['required', 'string', 'max:255'],
            'form.code' => ['required', 'string', 'max:50'],
            'form.contact_name' => ['nullable', 'string', 'max:255'],
            'form.email' => ['nullable', 'email', 'max:255'],
            'form.phone' => ['nullable', 'string', 'max:50'],
            'form.tax_number' => ['nullable', 'string', 'max:50'],
            'form.address' => ['nullable', 'string'],
            'form.payment_terms' => ['nullable', 'string', 'max:50'],
            'form.is_active' => ['boolean'],
            'form.notes' => ['nullable', 'string'],
        ]);

        session()->flash('status', 'Supplier created (demo only).');

        $this->dispatch('notify', message: 'Supplier created');

        $this->redirect(route('procurement.suppliers'), navigate: true);
    }

    public function render(): View
    {
        $stats = [
            'totalSuppliers' => 48,
            'active' => 42,
            'avgLeadTime' => 7,
        ];

        return view('livewire.procurement.suppliers.create', [
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Add Supplier',
            'pageTagline' => 'Procurement',
            'activeModule' => 'procurement',
            'navLinks' => ProcurementNavigation::links('suppliers'),
        ]);
    }
}
