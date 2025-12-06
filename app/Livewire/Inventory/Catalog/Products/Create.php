<?php

namespace App\Livewire\Inventory\Catalog\Products;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Product;
use App\Support\InventoryNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Create extends Component
{
    public ?int $product_id = null;
    public ?string $selling_price = null;
    public ?string $cost_price = null;
    public int $stock_quantity = 0;
    public int $min_stock_level = 5;
    public ?int $max_stock_level = null;
    public bool $is_available = true;
    public bool $is_featured = false;

    public function updatedProductId($value): void
    {
        if ($value) {
            $product = Product::find($value);
            if ($product) {
                $this->selling_price = (string) $product->selling_price;
                $this->cost_price = (string) $product->cost_price;
            }
        }
    }

    public function save(): void
    {
        $branchId = (int) session('active_branch_id', 0);

        if (!$branchId) {
            session()->flash('flash', [
                'type' => 'error',
                'title' => 'No branch selected',
                'message' => 'Please select a branch first.',
            ]);
            return;
        }

        $this->validate([
            'product_id' => ['required', 'exists:products,id'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'min_stock_level' => ['required', 'integer', 'min:0'],
            'max_stock_level' => ['nullable', 'integer', 'min:0'],
        ]);

        // Check if product already exists in branch
        $exists = BranchProduct::where('branch_id', $branchId)
            ->where('product_id', $this->product_id)
            ->exists();

        if ($exists) {
            $this->addError('product_id', 'This product is already added to this branch.');
            return;
        }

        BranchProduct::create([
            'branch_id' => $branchId,
            'product_id' => $this->product_id,
            'selling_price' => $this->selling_price ?: null,
            'cost_price' => $this->cost_price ?: null,
            'stock_quantity' => $this->stock_quantity,
            'min_stock_level' => $this->min_stock_level,
            'max_stock_level' => $this->max_stock_level,
            'is_available' => $this->is_available,
            'is_featured' => $this->is_featured,
        ]);

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Product added',
            'message' => 'Product has been added to this branch.',
        ]);

        $this->redirectRoute('inventory.catalog.products', navigate: true);
    }

    public function render(): View
    {
        $branchId = (int) session('active_branch_id', 0);
        $activeBranch = $branchId ? Branch::find($branchId) : null;

        // Get products not yet added to this branch
        $existingProductIds = $branchId
            ? BranchProduct::where('branch_id', $branchId)->pluck('product_id')
            : collect();

        $availableProducts = Product::whereNotIn('id', $existingProductIds)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'selling_price', 'cost_price']);

        return view('livewire.inventory.catalog.products.create', [
            'activeBranch' => $activeBranch,
            'availableProducts' => $availableProducts,
        ])->layoutData([
            'pageTitle' => 'Add Product',
            'pageTagline' => 'Inventory · Catalog',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('catalog', 'products'),
        ]);
    }
}
