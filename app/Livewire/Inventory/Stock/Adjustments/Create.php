<?php

namespace App\Livewire\Inventory\Stock\Adjustments;

use App\Enums\StockAdjustmentStatus;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\Warehouse;
use App\Support\InventoryNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Create extends Component
{
    public ?string $warehouse_id = null;
    public string $type = 'addition';
    public ?string $adjustment_date = null;
    public string $reason = '';
    public string $notes = '';

    /** @var array<int, array<string, mixed>> */
    public array $items = [];

    public function mount(): void
    {
        $this->adjustment_date = now()->format('Y-m-d');

        if (empty($this->items)) {
            $this->addItem();
        }
    }

    public function addItem(): void
    {
        $this->items[] = [
            'product_id' => '',
            'quantity' => 1,
        ];

        $this->dispatch('adjustment-item-added');
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    protected function generateReference(): string
    {
        $warehouse = $this->warehouse_id ? Warehouse::query()->find($this->warehouse_id) : null;
        $warehouseCode = $warehouse?->code ?: 'WH';

        $date = $this->adjustment_date
            ? Carbon::parse($this->adjustment_date)
            : now();

        $datePart = $date->format('dmY'); // DayMonthYear, e.g. 29012025

        return 'ADJ-'.strtoupper($warehouseCode).'-'.$datePart;
    }

    public function save(): void
    {
        $this->validate([
            'warehouse_id' => ['required', 'string'],
            'type' => ['required', 'string'],
            'adjustment_date' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'items' => ['array', 'min:1'],
            'items.*.product_id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $reference = $this->generateReference();
        $adjustmentId = null;

        DB::transaction(function () use ($reference, &$adjustmentId) {
            $adjustment = StockAdjustment::create([
                'reference' => $reference,
                'warehouse_id' => $this->warehouse_id,
                'type' => $this->type,
                'status' => StockAdjustmentStatus::Draft,
                'adjustment_date' => $this->adjustment_date,
                'reason' => $this->reason,
                'notes' => $this->notes,
            ]);

            $adjustmentId = $adjustment->id;

            foreach ($this->items as $item) {
                $product = Product::find($item['product_id']);

                if (! $product) {
                    continue;
                }

                $adjustment->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                ]);

                if ($this->type === 'addition') {
                    $product->increment('stock_quantity', $item['quantity']);
                } else {
                    $product->decrement('stock_quantity', $item['quantity']);
                }
            }
        });

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Stock adjustment created',
            'message' => "Stock adjustment {$reference} created",
        ]);

        $this->dispatch('notify', message: "Stock adjustment {$reference} created");

        if ($adjustmentId) {
            $this->redirectRoute('inventory.stock.adjustments.show', ['adjustment' => $adjustmentId], navigate: true);
        } else {
            $this->redirect(route('inventory.stock.adjustments'), navigate: true);
        }
    }

    public function render(): View
    {
        $warehouses = Warehouse::orderBy('name')->with('branch')->get();
        $products = Product::orderBy('name')->limit(100)->get();

        return view('livewire.inventory.stock.adjustments.create', [
            'warehouses' => $warehouses,
            'products' => $products,
        ])->layoutData([
            'pageTitle' => 'New Stock Adjustment',
            'pageTagline' => 'Inventory · Stock',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('stock', 'adjustments'),
        ]);
    }
}
