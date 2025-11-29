<?php

namespace App\Livewire\Procurement\Suppliers;

use App\Support\ProcurementNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    public int $supplierId;

    /**
     * @var array<string, mixed>
     */
    public array $form = [];

    public function mount(int $supplier): void
    {
        $this->supplierId = $supplier;

        // Mock supplier data for demo purposes
        $supplierData = [
            'name' => 'PT Supplier Utama',
            'code' => 'SUP-00'.$supplier,
            'contact_name' => 'Budi Santoso',
            'email' => 'budi@supplier.com',
            'phone' => '021-1234567',
            'tax_number' => '01.234.567.8-901.000',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'payment_terms' => '30',
            'is_active' => true,
            'notes' => 'Top tier supplier with consistent lead times.',
        ];

        $this->form = $supplierData;
    }

    public function save(): void
    {
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

        session()->flash('status', 'Supplier updated (demo only).');

        $this->dispatch('notify', message: 'Supplier updated');

        $this->redirect(route('procurement.suppliers'), navigate: true);
    }

    public function render(): View
    {
        // Mock stats & related orders similar to a Filament relation manager
        $stats = [
            'lifetimeSpend' => 125000000,
            'ordersCount' => 45,
            'openOrders' => 3,
            'onTimeRate' => 96,
        ];

        $orders = collect([
            ['number' => 'PO-2024-045', 'date' => now()->subDays(2), 'status' => 'open', 'amount' => 12500000],
            ['number' => 'PO-2024-044', 'date' => now()->subDays(7), 'status' => 'completed', 'amount' => 9800000],
            ['number' => 'PO-2024-043', 'date' => now()->subDays(14), 'status' => 'completed', 'amount' => 15400000],
        ]);

        return view('livewire.procurement.suppliers.edit', [
            'stats' => $stats,
            'orders' => $orders,
        ])->layoutData([
            'pageTitle' => 'Edit Supplier',
            'pageTagline' => 'Procurement',
            'activeModule' => 'procurement',
            'navLinks' => ProcurementNavigation::links('suppliers'),
        ]);
    }
}
