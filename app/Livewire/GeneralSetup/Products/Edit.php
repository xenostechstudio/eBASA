<?php

namespace App\Livewire\GeneralSetup\Products;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Support\GeneralSetupNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.portal-sidebar')]
#[Title('Edit Product')]
class Edit extends Component
{
    use WithFileUploads;

    public ?int $productId = null;
    public ?Product $editingProduct = null;

    public string $sku = '';
    public string $name = '';
    public string $brand = '';
    public string $barcode = '';
    public string $description = '';
    public ?int $category_id = null;
    public string $cost_price = '0';
    public string $selling_price = '0';
    public string $unit = 'pcs';
    public int $stock_quantity = 0;
    public int $min_stock_level = 0;
    public bool $is_active = true;
    public bool $track_inventory = true;

    public string $size = '';
    public string $color = '';
    public string $tax_group = '';
    public string $internal_notes = '';

    /** @var mixed */
    public $image;

    public ?string $image_path = null;

    public function mount(Product $product): void
    {
        $this->productId = $product->id;
        $this->editingProduct = $product->load(['createdBy', 'updatedBy']);

        $this->sku = $product->sku;
        $this->name = $product->name;
        $this->brand = $product->brand ?? '';
        $this->barcode = $product->barcode ?? '';
        $this->description = $product->description ?? '';
        $this->category_id = $product->category_id;
        $this->cost_price = (string) $product->cost_price;
        $this->selling_price = (string) $product->selling_price;
        $this->unit = $product->unit ?? 'pcs';
        $this->stock_quantity = $product->stock_quantity ?? 0;
        $this->min_stock_level = $product->min_stock_level ?? 0;
        $this->is_active = (bool) $product->is_active;
        $this->track_inventory = (bool) $product->track_inventory;
        $this->size = $product->size ?? '';
        $this->color = $product->color ?? '';
        $this->tax_group = $product->tax_group ?? '';
        $this->internal_notes = $product->internal_notes ?? '';
        $this->image_path = $product->image_path;
        $this->image = null;
    }

    #[Computed]
    public function categories()
    {
        return ProductCategory::orderBy('sort_order')->get();
    }

    protected function rules(): array
    {
        $id = $this->productId ?? 'NULL';

        return [
            'sku' => 'required|string|max:50|unique:products,sku,' . $id,
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:product_categories,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'size' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:100',
            'tax_group' => 'nullable|string|max:100',
            'internal_notes' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
            'track_inventory' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function save(): void
    {
        $this->validate();

        if (! $this->productId) {
            return;
        }

        $product = Product::find($this->productId);

        if (! $product) {
            return;
        }

        $imagePath = $this->image_path;

        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        $product->update([
            'sku' => $this->sku,
            'name' => $this->name,
            'brand' => $this->brand ?: null,
            'barcode' => $this->barcode ?: null,
            'description' => $this->description ?: null,
            'category_id' => $this->category_id,
            'cost_price' => $this->cost_price,
            'selling_price' => $this->selling_price,
            'unit' => $this->unit,
            'stock_quantity' => $this->stock_quantity,
            'min_stock_level' => $this->min_stock_level,
            'size' => $this->size ?: null,
            'color' => $this->color ?: null,
            'tax_group' => $this->tax_group ?: null,
            'is_active' => $this->is_active,
            'track_inventory' => $this->track_inventory,
            'image_path' => $imagePath,
            'internal_notes' => $this->internal_notes ?: null,
        ]);

        $this->editingProduct = $product->fresh(['createdBy', 'updatedBy']);

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Product updated',
            'message' => 'Product updated successfully.',
        ]);
    }

    public function closeModal(): void
    {
        $this->redirectRoute('general-setup.products.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.general-setup.products.edit', [
            'editingProduct' => $this->editingProduct,
        ])->layoutData([
            'pageTitle' => 'Edit Product',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('products'),
        ]);
    }
}
