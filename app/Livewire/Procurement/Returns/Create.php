<?php

namespace App\Livewire\Procurement\Returns;

use App\Support\ProcurementNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Create extends Component
{
    public string $supplier_id = '';
    public string $reason = '';
    public string $notes = '';

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $items = [];

    public function addItem(): void
    {
        $this->items[] = [
            'product_id' => '',
            'quantity' => 1,
            'reason' => '',
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save(): void
    {
        $this->validate([
            'supplier_id' => ['required', 'string'],
            'reason' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'items' => ['array', 'min:1'],
            'items.*.product_id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.reason' => ['nullable', 'string', 'max:255'],
        ]);

        $this->dispatch('notify', message: 'Return created (demo only)');

        $this->redirect(route('procurement.returns'), navigate: true);
    }

    public function render(): View
    {
        $suppliers = collect([
            (object) ['id' => 1, 'name' => 'PT Supplier Utama'],
            (object) ['id' => 2, 'name' => 'CV Mitra Jaya'],
            (object) ['id' => 3, 'name' => 'UD Berkah Makmur'],
        ]);

        $products = collect([
            (object) ['id' => 1, 'name' => 'Product A', 'sku' => 'SKU-001'],
            (object) ['id' => 2, 'name' => 'Product B', 'sku' => 'SKU-002'],
            (object) ['id' => 3, 'name' => 'Product C', 'sku' => 'SKU-003'],
        ]);

        return view('livewire.procurement.returns.create', [
            'suppliers' => $suppliers,
            'products' => $products,
        ])->layoutData([
            'pageTitle' => 'Create Return',
            'pageTagline' => 'Procurement',
            'activeModule' => 'procurement',
            'navLinks' => ProcurementNavigation::links('returns'),
        ]);
    }
}
