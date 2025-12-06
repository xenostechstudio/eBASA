<?php

namespace App\Livewire\Inventory\Catalog\PriceLists;

use App\Models\Branch;
use App\Models\PriceList;
use App\Models\Product;
use App\Support\InventoryNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    public PriceList $priceList;

    public string $code = '';
    public string $name = '';
    public string $description = '';
    public string $type = 'retail';
    public int $priority = 0;
    public ?string $valid_from = null;
    public ?string $valid_until = null;
    public bool $is_active = true;
    public bool $is_default = false;

    /** @var array<int, array<string, mixed>> */
    public array $priceListItems = [];

    public function mount(PriceList $priceList): void
    {
        $this->priceList = $priceList;
        $this->code = $priceList->code;
        $this->name = $priceList->name;
        $this->description = $priceList->description ?? '';
        $this->type = $priceList->type;
        $this->priority = $priceList->priority;
        $this->valid_from = $priceList->valid_from?->format('Y-m-d');
        $this->valid_until = $priceList->valid_until?->format('Y-m-d');
        $this->is_active = $priceList->is_active;
        $this->is_default = $priceList->is_default;

        $this->priceListItems = $priceList->items->map(fn ($item) => [
            'id' => $item->id,
            'product_id' => (string) $item->product_id,
            'price' => $item->price,
            'discount_percent' => $item->discount_percent,
            'min_qty' => $item->min_qty,
        ])->toArray();

        if (empty($this->priceListItems)) {
            $this->addPriceListItem();
        }
    }

    public function addPriceListItem(): void
    {
        $this->priceListItems[] = [
            'id' => null,
            'product_id' => '',
            'price' => 0,
            'discount_percent' => 0,
            'min_qty' => 1,
        ];
    }

    public function removePriceListItem(int $index): void
    {
        unset($this->priceListItems[$index]);
        $this->priceListItems = array_values($this->priceListItems);
    }

    public function updatedPriceListItems($value, $key): void
    {
        if (str_contains($key, 'product_id') && $value) {
            $index = (int) explode('.', $key)[0];
            $product = Product::find($value);
            if ($product) {
                $this->priceListItems[$index]['price'] = $product->selling_price ?? 0;
            }
        }
    }

    public function save(): void
    {
        $this->validate([
            'code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:retail,wholesale,member,promo,custom'],
            'priority' => ['required', 'integer', 'min:0'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'priceListItems' => ['required', 'array', 'min:1'],
            'priceListItems.*.product_id' => ['required', 'exists:products,id'],
            'priceListItems.*.price' => ['required', 'numeric', 'min:0'],
            'priceListItems.*.discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'priceListItems.*.min_qty' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () {
            // If setting as default, unset other defaults
            if ($this->is_default && !$this->priceList->is_default) {
                PriceList::where('branch_id', $this->priceList->branch_id)
                    ->where('id', '!=', $this->priceList->id)
                    ->update(['is_default' => false]);
            }

            $this->priceList->update([
                'code' => $this->code,
                'name' => $this->name,
                'description' => $this->description,
                'type' => $this->type,
                'priority' => $this->priority,
                'valid_from' => $this->valid_from,
                'valid_until' => $this->valid_until,
                'is_active' => $this->is_active,
                'is_default' => $this->is_default,
            ]);

            // Delete removed items
            $existingIds = collect($this->priceListItems)->pluck('id')->filter()->toArray();
            $this->priceList->items()->whereNotIn('id', $existingIds)->delete();

            // Update or create items
            foreach ($this->priceListItems as $item) {
                $this->priceList->items()->updateOrCreate(
                    ['id' => $item['id'] ?? null],
                    [
                        'product_id' => $item['product_id'],
                        'price' => $item['price'],
                        'discount_percent' => $item['discount_percent'] ?? 0,
                        'min_qty' => $item['min_qty'],
                    ]
                );
            }
        });

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Price list updated',
            'message' => "Price list {$this->name} has been updated successfully.",
        ]);

        $this->redirectRoute('inventory.catalog.price-lists', navigate: true);
    }

    public function delete(): void
    {
        $name = $this->priceList->name;
        $this->priceList->items()->delete();
        $this->priceList->delete();

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Price list deleted',
            'message' => "Price list {$name} has been deleted.",
        ]);

        $this->redirectRoute('inventory.catalog.price-lists', navigate: true);
    }

    public function render(): View
    {
        $branchId = (int) session('active_branch_id', 0);
        $activeBranch = $branchId ? Branch::find($branchId) : null;

        return view('livewire.inventory.catalog.price-lists.edit', [
            'products' => Product::where('is_active', true)->orderBy('name')->get(['id', 'name', 'sku', 'selling_price']),
            'activeBranch' => $activeBranch,
            'types' => PriceList::TYPES,
        ])->layoutData([
            'pageTitle' => 'Edit Price List',
            'pageTagline' => 'Inventory · Catalog',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('catalog', 'price-lists'),
        ]);
    }
}
