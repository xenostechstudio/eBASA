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
class Create extends Component
{
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

    public function mount(): void
    {
        $this->code = $this->generateCode();
        $this->valid_from = now()->format('Y-m-d');
        $this->addPriceListItem();
    }

    protected function generateCode(): string
    {
        $branchId = (int) session('active_branch_id', 0);
        $branch = $branchId ? Branch::find($branchId) : null;
        $branchCode = $branch?->code ?? 'PL';
        $count = PriceList::where('branch_id', $branchId)->count() + 1;

        return strtoupper($branchCode) . '-PL-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function addPriceListItem(): void
    {
        $this->priceListItems[] = [
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
        $branchId = (int) session('active_branch_id', 0);

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

        DB::transaction(function () use ($branchId) {
            // If setting as default, unset other defaults
            if ($this->is_default) {
                PriceList::where('branch_id', $branchId)->update(['is_default' => false]);
            }

            $priceList = PriceList::create([
                'branch_id' => $branchId,
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

            foreach ($this->priceListItems as $item) {
                $priceList->items()->create([
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'discount_percent' => $item['discount_percent'] ?? 0,
                    'min_qty' => $item['min_qty'],
                ]);
            }
        });

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Price list created',
            'message' => "Price list {$this->name} has been created successfully.",
        ]);

        $this->redirectRoute('inventory.catalog.price-lists', navigate: true);
    }

    public function render(): View
    {
        $branchId = (int) session('active_branch_id', 0);
        $activeBranch = $branchId ? Branch::find($branchId) : null;

        return view('livewire.inventory.catalog.price-lists.create', [
            'products' => Product::where('is_active', true)->orderBy('name')->get(['id', 'name', 'sku', 'selling_price']),
            'activeBranch' => $activeBranch,
            'types' => PriceList::TYPES,
        ])->layoutData([
            'pageTitle' => 'Create Price List',
            'pageTagline' => 'Inventory · Catalog',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('catalog', 'price-lists'),
        ]);
    }
}
