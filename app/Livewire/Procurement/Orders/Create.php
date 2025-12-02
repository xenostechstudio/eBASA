<?php

namespace App\Livewire\Procurement\Orders;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
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
    public ?int $supplier = null;

    public ?int $supplier_id = null;
    public ?int $warehouse_id = null;
    public ?string $expected_delivery_date = null;
    public ?string $requested_by = null;
    public string $payment_terms = '30_days';
    public ?string $delivery_instructions = null;
    public string $notes = '';

    public array $items = [];

    // Product search for adding items
    public string $productSearch = '';
    public bool $showProductPicker = false;

    public function mount(): void
    {
        // Pre-select supplier if passed via URL
        if ($this->supplier) {
            $this->supplier_id = $this->supplier;
            $supplier = Supplier::find($this->supplier);
            if ($supplier) {
                $this->payment_terms = $supplier->payment_terms ? $supplier->payment_terms . '_days' : '30_days';
            }
        }
    }

    public function updatedSupplierId(): void
    {
        // Clear items when supplier changes (products are supplier-specific)
        $this->items = [];
        $this->productSearch = '';

        // Update payment terms from supplier
        if ($this->supplier_id) {
            $supplier = Supplier::find($this->supplier_id);
            if ($supplier && $supplier->payment_terms) {
                $this->payment_terms = $supplier->payment_terms . '_days';
            }
        }
    }

    #[Computed]
    public function suppliers()
    {
        return Supplier::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function warehouses()
    {
        return Warehouse::with('branch')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function supplierProducts()
    {
        if (!$this->supplier_id) {
            return collect();
        }

        $addedProductIds = collect($this->items)->pluck('product_id')->filter()->toArray();

        return Supplier::find($this->supplier_id)
            ?->products()
            ->when($this->productSearch, fn ($q) => $q->where('name', 'like', "%{$this->productSearch}%"))
            ->whereNotIn('products.id', $addedProductIds)
            ->limit(20)
            ->get() ?? collect();
    }

    public function openProductPicker(): void
    {
        if (!$this->supplier_id) {
            $this->dispatch('notify', message: 'Please select a supplier first', type: 'warning');
            return;
        }
        $this->productSearch = '';
        $this->showProductPicker = true;
    }

    public function addProduct(int $productId): void
    {
        $supplier = Supplier::find($this->supplier_id);
        $product = $supplier?->products()->where('products.id', $productId)->first();

        if (!$product) {
            return;
        }

        $this->items[] = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'quantity' => $product->pivot->min_order_qty ?? 1,
            'unit_price' => $product->pivot->supplier_price ?? $product->cost_price ?? 0,
            'tax_rate' => 0,
        ];

        unset($this->supplierProducts);
        $this->showProductPicker = false;
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        unset($this->supplierProducts);
    }

    public function updateItemQuantity(int $index, int $quantity): void
    {
        if (isset($this->items[$index])) {
            $this->items[$index]['quantity'] = max(1, $quantity);
        }
    }

    public function updateItemPrice(int $index, float $price): void
    {
        if (isset($this->items[$index])) {
            $this->items[$index]['unit_price'] = max(0, $price);
        }
    }

    #[Computed]
    public function subtotal(): float
    {
        return collect($this->items)->sum(fn ($item) => ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0));
    }

    #[Computed]
    public function taxAmount(): float
    {
        return collect($this->items)->sum(function ($item) {
            $subtotal = ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            return $subtotal * (($item['tax_rate'] ?? 0) / 100);
        });
    }

    #[Computed]
    public function total(): float
    {
        return $this->subtotal + $this->taxAmount;
    }

    public function save(): void
    {
        $this->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'expected_delivery_date' => ['nullable', 'date', 'after_or_equal:today'],
            'requested_by' => ['nullable', 'string', 'max:120'],
            'payment_terms' => ['nullable', 'string', 'max:50'],
            'delivery_instructions' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () {
            $po = PurchaseOrder::create([
                'reference' => PurchaseOrder::generateReference(),
                'supplier_id' => $this->supplier_id,
                'warehouse_id' => $this->warehouse_id,
                'order_date' => now(),
                'expected_delivery_date' => $this->expected_delivery_date,
                'status' => PurchaseOrder::STATUS_DRAFT,
                'payment_terms' => $this->payment_terms,
                'requested_by' => $this->requested_by,
                'delivery_instructions' => $this->delivery_instructions,
                'notes' => $this->notes,
            ]);

            foreach ($this->items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $taxAmount = $subtotal * (($item['tax_rate'] ?? 0) / 100);

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'] ?? 0,
                    'tax_amount' => $taxAmount,
                    'subtotal' => $subtotal,
                    'total' => $subtotal + $taxAmount,
                ]);
            }

            $po->recalculateTotals();
        });

        $this->dispatch('notify', message: 'Purchase order created successfully');
        $this->redirect(route('procurement.orders'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.procurement.orders.create')->layoutData([
            'pageTitle' => 'Create Purchase Order',
            'pageTagline' => 'Procurement',
            'activeModule' => 'procurement',
            'navLinks' => ProcurementNavigation::links('orders', 'create'),
        ]);
    }
}
