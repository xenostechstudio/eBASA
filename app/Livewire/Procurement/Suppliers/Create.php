<?php

namespace App\Livewire\Procurement\Suppliers;

use App\Models\Supplier;
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
        'payment_terms' => 30,
        'is_active' => true,
        'notes' => '',
    ];

    public function mount(): void
    {
        // Auto-generate supplier code
        $this->form['code'] = Supplier::generateCode();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'form.name' => ['required', 'string', 'max:255'],
            'form.code' => ['required', 'string', 'max:50', 'unique:suppliers,code'],
            'form.contact_name' => ['nullable', 'string', 'max:255'],
            'form.email' => ['nullable', 'email', 'max:255'],
            'form.phone' => ['nullable', 'string', 'max:50'],
            'form.tax_number' => ['nullable', 'string', 'max:50'],
            'form.address' => ['nullable', 'string'],
            'form.payment_terms' => ['nullable', 'integer', 'min:0'],
            'form.is_active' => ['boolean'],
            'form.notes' => ['nullable', 'string'],
        ]);

        $supplier = Supplier::create($validated['form']);

        session()->flash('status', 'Supplier created successfully');

        $this->dispatch('notify', message: 'Supplier created successfully');

        $this->redirect(route('procurement.suppliers.edit', $supplier), navigate: true);
    }

    public function render(): View
    {
        $stats = [
            'totalSuppliers' => Supplier::count(),
            'active' => Supplier::where('is_active', true)->count(),
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
