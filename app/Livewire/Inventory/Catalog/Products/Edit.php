<?php

namespace App\Livewire\Inventory\Catalog\Products;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Support\InventoryNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    public BranchProduct $branchProduct;

    public ?string $selling_price = null;
    public ?string $cost_price = null;
    public int $stock_quantity = 0;
    public int $min_stock_level = 0;
    public ?int $max_stock_level = null;
    public bool $is_available = true;
    public bool $is_featured = false;

    public function mount(BranchProduct $branchProduct): void
    {
        $this->branchProduct = $branchProduct;
        $this->selling_price = $branchProduct->selling_price ? (string) $branchProduct->selling_price : null;
        $this->cost_price = $branchProduct->cost_price ? (string) $branchProduct->cost_price : null;
        $this->stock_quantity = $branchProduct->stock_quantity;
        $this->min_stock_level = $branchProduct->min_stock_level;
        $this->max_stock_level = $branchProduct->max_stock_level;
        $this->is_available = $branchProduct->is_available;
        $this->is_featured = $branchProduct->is_featured;
    }

    public function save(): void
    {
        $this->validate([
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'min_stock_level' => ['required', 'integer', 'min:0'],
            'max_stock_level' => ['nullable', 'integer', 'min:0'],
        ]);

        $this->branchProduct->update([
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
            'title' => 'Product updated',
            'message' => 'Product configuration has been updated.',
        ]);

        $this->redirectRoute('inventory.catalog.products', navigate: true);
    }

    public function delete(): void
    {
        $name = $this->branchProduct->product->name ?? 'Product';
        $this->branchProduct->delete();

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Product removed',
            'message' => "{$name} has been removed from this branch.",
        ]);

        $this->redirectRoute('inventory.catalog.products', navigate: true);
    }

    public function render(): View
    {
        $branchId = (int) session('active_branch_id', 0);
        $activeBranch = $branchId ? Branch::find($branchId) : null;

        return view('livewire.inventory.catalog.products.edit', [
            'activeBranch' => $activeBranch,
        ])->layoutData([
            'pageTitle' => 'Edit Product',
            'pageTagline' => 'Inventory · Catalog',
            'activeModule' => 'inventory',
            'navLinks' => InventoryNavigation::links('catalog', 'products'),
        ]);
    }
}
