<?php

namespace App\Livewire\Procurement\Receipts;

use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\PurchaseOrder;
use App\Models\Warehouse;
use App\Support\ProcurementNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Create extends Component
{
    #[Url]
    public ?int $po = null;

    public ?int $purchase_order_id = null;
    public ?int $warehouse_id = null;
    public ?string $received_date = null;
    public ?string $received_by_name = null;
    public ?string $delivery_note_number = null;
    public string $notes = '';

    public array $items = [];

    public function mount(): void
    {
        $this->received_date = now()->format('Y-m-d');

        // Pre-select PO if passed via URL
        if ($this->po) {
            $this->purchase_order_id = $this->po;
            $this->loadPurchaseOrderItems();
        }
    }

    public function updatedPurchaseOrderId(): void
    {
        $this->loadPurchaseOrderItems();
    }

    protected function loadPurchaseOrderItems(): void
    {
        $this->items = [];

        if (!$this->purchase_order_id) {
            return;
        }

        $po = PurchaseOrder::with('items.product', 'warehouse')->find($this->purchase_order_id);

        if (!$po || !$po->canReceive()) {
            $this->purchase_order_id = null;
            $this->dispatch('notify', message: 'This PO cannot receive goods', type: 'error');
            return;
        }

        // Pre-fill warehouse from PO
        if ($po->warehouse_id && !$this->warehouse_id) {
            $this->warehouse_id = $po->warehouse_id;
        }

        foreach ($po->items as $poItem) {
            $remaining = $poItem->remaining_quantity;

            if ($remaining <= 0) {
                continue; // Skip fully received items
            }

            $this->items[] = [
                'po_item_id' => $poItem->id,
                'product_id' => $poItem->product_id,
                'product_name' => $poItem->product->name,
                'product_sku' => $poItem->product->sku,
                'ordered_qty' => $poItem->quantity,
                'received_qty' => $poItem->received_quantity,
                'remaining_qty' => $remaining,
                'quantity_to_receive' => $remaining, // Default to full remaining
                'quantity_accepted' => $remaining,
                'quantity_rejected' => 0,
                'rejection_reason' => '',
            ];
        }
    }

    #[Computed]
    public function receivablePurchaseOrders()
    {
        return PurchaseOrder::with('supplier')
            ->receivable()
            ->orderByDesc('order_date')
            ->get();
    }

    #[Computed]
    public function selectedPurchaseOrder()
    {
        if (!$this->purchase_order_id) {
            return null;
        }

        return PurchaseOrder::with('supplier', 'warehouse')->find($this->purchase_order_id);
    }

    #[Computed]
    public function warehouses()
    {
        return Warehouse::with('branch')->orderBy('name')->get();
    }

    public function updateQuantityToReceive(int $index, int $qty): void
    {
        if (!isset($this->items[$index])) {
            return;
        }

        $max = $this->items[$index]['remaining_qty'];
        $qty = max(0, min($qty, $max));

        $this->items[$index]['quantity_to_receive'] = $qty;
        $this->items[$index]['quantity_accepted'] = $qty;
        $this->items[$index]['quantity_rejected'] = 0;
    }

    public function updateQuantityAccepted(int $index, int $qty): void
    {
        if (!isset($this->items[$index])) {
            return;
        }

        $toReceive = $this->items[$index]['quantity_to_receive'];
        $qty = max(0, min($qty, $toReceive));

        $this->items[$index]['quantity_accepted'] = $qty;
        $this->items[$index]['quantity_rejected'] = $toReceive - $qty;
    }

    #[Computed]
    public function totalToReceive(): int
    {
        return collect($this->items)->sum('quantity_to_receive');
    }

    #[Computed]
    public function totalAccepted(): int
    {
        return collect($this->items)->sum('quantity_accepted');
    }

    #[Computed]
    public function totalRejected(): int
    {
        return collect($this->items)->sum('quantity_rejected');
    }

    public function save(): void
    {
        $this->validate([
            'purchase_order_id' => ['required', 'exists:purchase_orders,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'received_date' => ['required', 'date'],
            'received_by_name' => ['nullable', 'string', 'max:255'],
            'delivery_note_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.quantity_to_receive' => ['required', 'integer', 'min:0'],
            'items.*.quantity_accepted' => ['required', 'integer', 'min:0'],
        ]);

        // Ensure at least one item is being received
        $hasItems = collect($this->items)->sum('quantity_to_receive') > 0;
        if (!$hasItems) {
            $this->addError('items', 'At least one item must have a quantity to receive.');
            return;
        }

        DB::transaction(function () {
            $gr = GoodsReceipt::create([
                'reference' => GoodsReceipt::generateReference(),
                'purchase_order_id' => $this->purchase_order_id,
                'warehouse_id' => $this->warehouse_id,
                'received_date' => $this->received_date,
                'status' => GoodsReceipt::STATUS_DRAFT,
                'received_by_name' => $this->received_by_name,
                'delivery_note_number' => $this->delivery_note_number,
                'notes' => $this->notes,
            ]);

            foreach ($this->items as $item) {
                if ($item['quantity_to_receive'] <= 0) {
                    continue;
                }

                GoodsReceiptItem::create([
                    'goods_receipt_id' => $gr->id,
                    'purchase_order_item_id' => $item['po_item_id'],
                    'product_id' => $item['product_id'],
                    'quantity_received' => $item['quantity_to_receive'],
                    'quantity_accepted' => $item['quantity_accepted'],
                    'quantity_rejected' => $item['quantity_rejected'],
                    'rejection_reason' => $item['rejection_reason'] ?? null,
                ]);
            }

            // Complete the receipt (updates PO item received quantities and stock)
            $gr->complete();
        });

        $this->dispatch('notify', message: 'Goods receipt created and completed successfully');
        $this->redirect(route('procurement.receipts'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.procurement.receipts.create')->layoutData([
            'pageTitle' => 'Receive Goods',
            'pageTagline' => 'Procurement',
            'activeModule' => 'procurement',
            'navLinks' => ProcurementNavigation::links('receipts', 'create'),
        ]);
    }
}
