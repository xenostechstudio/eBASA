<?php

namespace App\Livewire\Procurement\Orders;

use App\Support\ProcurementNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Create extends Component
{
    public string $supplier_id = '';
    public string $notes = '';
    public array $items = [];

    public function addItem(): void
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1, 'price' => 0];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save(): void
    {
        // Save logic here
        $this->dispatch('notify', message: 'Purchase order created successfully');
        $this->redirect(route('procurement.orders'));
    }

    public function render(): View
    {
        // Mock data
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

        return view('livewire.procurement.orders.create', [
            'suppliers' => $suppliers,
            'products' => $products,
        ])->layoutData([
            'pageTitle' => 'Create Purchase Order',
            'pageTagline' => 'Procurement',
            'activeModule' => 'procurement',
            'navLinks' => ProcurementNavigation::links('orders', 'create'),
        ]);
    }
}
