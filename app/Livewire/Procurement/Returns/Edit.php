<?php

namespace App\Livewire\Procurement\Returns;

use App\Support\ProcurementNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    public int $returnId;

    /**
     * @var array<string, mixed>
     */
    public array $form = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $items = [];

    public function mount(int $return): void
    {
        $this->returnId = $return;

        $this->form = [
            'reference' => 'RET-2024-00' . $return,
            'supplier_name' => 'PT Supplier Utama',
            'status' => 'pending',
            'reason' => 'Damaged goods',
            'notes' => 'Box received with visible damage on two items.',
            'created_at' => now()->subDays(2),
        ];

        $this->items = [
            ['product' => 'Product A', 'sku' => 'SKU-001', 'quantity' => 2, 'reason' => 'Broken packaging'],
            ['product' => 'Product B', 'sku' => 'SKU-002', 'quantity' => 1, 'reason' => 'Expired date'],
        ];
    }

    public function save(): void
    {
        $this->validate([
            'form.reason' => ['required', 'string', 'max:255'],
            'form.notes' => ['nullable', 'string'],
            'form.status' => ['required', 'string'],
        ]);

        $this->dispatch('notify', message: 'Return updated (demo only)');

        $this->redirect(route('procurement.returns'), navigate: true);
    }

    public function render(): View
    {
        $timeline = [
            ['label' => 'Created', 'time' => $this->form['created_at']->copy()->subHours(4), 'status' => 'done'],
            ['label' => 'Reviewed', 'time' => $this->form['created_at']->copy()->subHours(2), 'status' => 'done'],
            ['label' => 'Awaiting shipment', 'time' => $this->form['created_at'], 'status' => 'current'],
        ];

        $purchaseOrder = [
            'number' => 'PO-2024-045',
            'date' => $this->form['created_at']->copy()->subDays(3),
            'status' => 'partially_received',
            'amount' => 12500000,
        ];

        $goodsReceipt = [
            'number' => 'GRN-2024-032',
            'date' => $this->form['created_at']->copy()->subDay(),
            'status' => 'posted',
            'received_items' => 3,
        ];

        return view('livewire.procurement.returns.edit', [
            'timeline' => $timeline,
            'purchaseOrder' => $purchaseOrder,
            'goodsReceipt' => $goodsReceipt,
        ])->layoutData([
            'pageTitle' => 'Return ' . $this->form['reference'],
            'pageTagline' => 'Procurement',
            'activeModule' => 'procurement',
            'navLinks' => ProcurementNavigation::links('returns'),
        ]);
    }
}
