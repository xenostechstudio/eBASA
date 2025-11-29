<?php

namespace App\Livewire\Inventory\Stock\Transfers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Support\InventoryNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Create extends Component
{
    public ?string $from_warehouse_id = null;
    public ?string $to_warehouse_id = null;
    public ?string $transfer_date = null;
    public string $reason = '';
    public string $notes = '';

    /** @var array<int, array<string, mixed>> */
    public array $items = [];

    public function mount(): void
    {
        $this->transfer_date = now()->format('Y-m-d');
    }

    public function addItem(): void
    {
        $this->items[] = [
            'product_id' => '',
            'quantity' => 1,
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
            'from_warehouse_id' => ['required', 'string'],
            'to_warehouse_id' => ['required', 'string', 'different:from_warehouse_id'],
            'transfer_date' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'items' => ['array', 'min:1'],
            'items.*.product_id' => ['required', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        // Persist to database in real implementation
        $this->dispatch('notify', message: 'Stock transfer created (demo only)');

        $this->redirect(route('inventory.stock.transfers'), navigate: true);
    }

    public function render(): View
    {
        $warehouses = Warehouse::orderBy('name')->with('branch')->get();
        $products = Product::orderBy('name')->limit(100)->get();

        return view('livewire.inventory.stock.transfers.create', [
            'warehouses' => $warehouses,
            'products' => $products,
        ])->layoutData([
            'pageTitle' => 'New Stock Transfer',
            'pageTagline' => 'Inventory · Stock',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('stock', 'transfers'),
        ]);
    }
}
