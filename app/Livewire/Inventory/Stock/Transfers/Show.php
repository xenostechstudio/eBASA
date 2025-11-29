<?php

namespace App\Livewire\Inventory\Stock\Transfers;

use App\Support\InventoryNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Show extends Component
{
    public int $transferId;

    /** @var array<string, mixed>|null */
    public ?array $transferData = null;

    public function mount(int $transfer): void
    {
        $this->transferId = $transfer;

        $all = collect([
            ['id' => 1, 'reference' => 'TRF-001', 'from_warehouse' => 'Main Warehouse', 'to_warehouse' => 'Branch Pemalang', 'items_count' => 5, 'status' => 'completed', 'created_at' => now()->subDays(1)],
            ['id' => 2, 'reference' => 'TRF-002', 'from_warehouse' => 'Main Warehouse', 'to_warehouse' => 'Branch Tegal', 'items_count' => 12, 'status' => 'in_transit', 'created_at' => now()->subDays(2)],
            ['id' => 3, 'reference' => 'TRF-003', 'from_warehouse' => 'Branch Banjaran', 'to_warehouse' => 'Main Warehouse', 'items_count' => 3, 'status' => 'pending', 'created_at' => now()->subDays(3)],
        ]);

        $this->transferData = $all->firstWhere('id', $transfer) ?? $all->first();
    }

    public function render(): View
    {
        return view('livewire.inventory.stock.transfers.show', [
            'transfer' => $this->transferData,
        ])->layoutData([
            'pageTitle' => 'Transfer '.($this->transferData['reference'] ?? ''),
            'pageTagline' => 'Inventory · Stock',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('stock', 'transfers'),
        ]);
    }
}
