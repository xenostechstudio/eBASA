<?php

namespace App\Livewire\Inventory\Catalog\Bundles;

use App\Models\Branch;
use App\Models\Bundle;
use App\Models\Product;
use App\Support\InventoryNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    public Bundle $bundle;

    public string $sku = '';
    public string $name = '';
    public string $description = '';
    public string $bundle_price = '';
    public ?string $valid_from = null;
    public ?string $valid_until = null;
    public bool $is_active = true;

    /** @var array<int, array<string, mixed>> */
    public array $bundleItems = [];

    public function mount(Bundle $bundle): void
    {
        $this->bundle = $bundle;
        $this->sku = $bundle->sku;
        $this->name = $bundle->name;
        $this->description = $bundle->description ?? '';
        $this->bundle_price = (string) $bundle->bundle_price;
        $this->valid_from = $bundle->valid_from?->format('Y-m-d');
        $this->valid_until = $bundle->valid_until?->format('Y-m-d');
        $this->is_active = $bundle->is_active;

        $this->bundleItems = $bundle->items->map(fn ($item) => [
            'id' => $item->id,
            'product_id' => (string) $item->product_id,
            'quantity' => $item->quantity,
            'unit_price' => $item->unit_price,
        ])->toArray();

        if (empty($this->bundleItems)) {
            $this->addBundleItem();
        }
    }

    public function addBundleItem(): void
    {
        $this->bundleItems[] = [
            'id' => null,
            'product_id' => '',
            'quantity' => 1,
            'unit_price' => 0,
        ];
    }

    public function removeBundleItem(int $index): void
    {
        unset($this->bundleItems[$index]);
        $this->bundleItems = array_values($this->bundleItems);
    }

    public function updatedBundleItems($value, $key): void
    {
        if (str_contains($key, 'product_id') && $value) {
            $index = (int) explode('.', $key)[0];
            $product = Product::find($value);
            if ($product) {
                $this->bundleItems[$index]['unit_price'] = $product->selling_price ?? 0;
            }
        }
    }

    public function getOriginalPriceProperty(): float
    {
        return collect($this->bundleItems)->sum(function ($item) {
            return ((float) ($item['unit_price'] ?? 0)) * ((int) ($item['quantity'] ?? 1));
        });
    }

    public function getDiscountAmountProperty(): float
    {
        $bundlePrice = (float) ($this->bundle_price ?: 0);
        return max(0, $this->originalPrice - $bundlePrice);
    }

    public function getDiscountPercentProperty(): float
    {
        if ($this->originalPrice <= 0) {
            return 0;
        }
        return round(($this->discountAmount / $this->originalPrice) * 100, 1);
    }

    public function save(): void
    {
        $this->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:bundles,sku,' . $this->bundle->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'bundle_price' => ['required', 'numeric', 'min:0'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'bundleItems' => ['required', 'array', 'min:1'],
            'bundleItems.*.product_id' => ['required', 'exists:products,id'],
            'bundleItems.*.quantity' => ['required', 'integer', 'min:1'],
            'bundleItems.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () {
            $this->bundle->update([
                'sku' => $this->sku,
                'name' => $this->name,
                'description' => $this->description,
                'bundle_price' => $this->bundle_price,
                'original_price' => $this->originalPrice,
                'valid_from' => $this->valid_from,
                'valid_until' => $this->valid_until,
                'is_active' => $this->is_active,
            ]);

            // Delete removed items
            $existingIds = collect($this->bundleItems)->pluck('id')->filter()->toArray();
            $this->bundle->items()->whereNotIn('id', $existingIds)->delete();

            // Update or create items
            foreach ($this->bundleItems as $item) {
                $this->bundle->items()->updateOrCreate(
                    ['id' => $item['id'] ?? null],
                    [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['unit_price'] * $item['quantity'],
                    ]
                );
            }
        });

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Bundle updated',
            'message' => "Bundle {$this->name} has been updated successfully.",
        ]);

        $this->redirectRoute('inventory.catalog.bundles', navigate: true);
    }

    public function delete(): void
    {
        $name = $this->bundle->name;
        $this->bundle->items()->delete();
        $this->bundle->delete();

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Bundle deleted',
            'message' => "Bundle {$name} has been deleted.",
        ]);

        $this->redirectRoute('inventory.catalog.bundles', navigate: true);
    }

    public function render(): View
    {
        $branchId = (int) session('active_branch_id', 0);
        $activeBranch = $branchId ? Branch::find($branchId) : null;

        return view('livewire.inventory.catalog.bundles.edit', [
            'products' => Product::where('is_active', true)->orderBy('name')->get(['id', 'name', 'sku', 'selling_price']),
            'activeBranch' => $activeBranch,
        ])->layoutData([
            'pageTitle' => 'Edit Bundle',
            'pageTagline' => 'Inventory · Catalog',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('catalog', 'bundles'),
        ]);
    }
}
