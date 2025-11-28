<?php

namespace App\Livewire\GeneralSetup\Products;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Support\GeneralSetupNavigation;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
#[Title('Products')]
class Index extends Component
{
    use WithPagination, WithFileUploads;

    #[Url]
    public string $search = '';

    #[Url]
    public string $categoryFilter = '';

    #[Url]
    public string $statusFilter = '';

    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    public int $perPage = 15;

    public array $perPageOptions = [15, 30, 50];

    public bool $showDeleteModal = false;
    public ?int $productToDelete = null;

    // Modal state
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public ?int $editingProductId = null;

    // Form fields
    public string $sku = '';
    public string $name = '';
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

    // Image upload
    public $image;

    public ?string $image_path = null;

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal(int $productId): void
    {
        $product = Product::find($productId);
        if ($product) {
            $this->editingProductId = $productId;
            $this->sku = $product->sku;
            $this->name = $product->name;
            $this->barcode = $product->barcode ?? '';
            $this->description = $product->description ?? '';
            $this->category_id = $product->category_id;
            $this->cost_price = (string) $product->cost_price;
            $this->selling_price = (string) $product->selling_price;
            $this->unit = $product->unit ?? 'pcs';
            $this->stock_quantity = $product->stock_quantity ?? 0;
            $this->min_stock_level = $product->min_stock_level ?? 0;
            $this->is_active = $product->is_active;
            $this->track_inventory = $product->track_inventory;
            $this->image_path = $product->image_path;
            $this->image = null;
            $this->showEditModal = true;
        }
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->editingProductId = null;
        $this->sku = '';
        $this->name = '';
        $this->barcode = '';
        $this->description = '';
        $this->category_id = null;
        $this->cost_price = '0';
        $this->selling_price = '0';
        $this->unit = 'pcs';
        $this->stock_quantity = 0;
        $this->min_stock_level = 0;
        $this->is_active = true;
        $this->track_inventory = true;
        $this->image = null;
        $this->image_path = null;
        $this->resetValidation();
    }

    public function save(): void
    {
        $isEditing = ! is_null($this->editingProductId);

        $rules = [
            'sku' => 'required|string|max:50|unique:products,sku' . ($isEditing ? ',' . $this->editingProductId : ''),
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:product_categories,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'track_inventory' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ];

        $this->validate($rules);

        $imagePath = $this->image_path;

        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        $data = [
            'sku' => $this->sku,
            'name' => $this->name,
            'barcode' => $this->barcode ?: null,
            'description' => $this->description ?: null,
            'category_id' => $this->category_id,
            'cost_price' => $this->cost_price,
            'selling_price' => $this->selling_price,
            'unit' => $this->unit,
            'stock_quantity' => $this->stock_quantity,
            'min_stock_level' => $this->min_stock_level,
            'is_active' => $this->is_active,
            'track_inventory' => $this->track_inventory,
            'image_path' => $imagePath,
        ];

        if ($isEditing) {
            $product = Product::find($this->editingProductId);
            if ($product) {
                $product->update($data);
            }

            $flashMessage = 'Product updated successfully.';
            $flashTitle = 'Product updated';
        } else {
            Product::create($data);

            $flashMessage = 'Product created successfully.';
            $flashTitle = 'Product created';
        }

        $this->closeModal();

        session()->flash('flash', [
            'type' => 'success',
            'title' => $flashTitle,
            'message' => $flashMessage,
        ]);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function setStatusFilter(string $value): void
    {
        $this->statusFilter = $value;
        $this->resetPage();
    }

    public function setCategoryFilter(string $value): void
    {
        $this->categoryFilter = $value;
        $this->resetPage();
    }

    public function updatedPerPage($value): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->productToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteProduct(): void
    {
        if ($this->productToDelete) {
            Product::find($this->productToDelete)?->delete();

            session()->flash('flash', [
                'type' => 'success',
                'title' => 'Product deleted',
                'message' => 'Product deleted successfully.',
            ]);
        }

        $this->showDeleteModal = false;
        $this->productToDelete = null;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->productToDelete = null;
    }

    public function export(string $format): void
    {
        if (! in_array($format, ['excel', 'pdf'], true)) {
            return;
        }

        $label = strtoupper($format);

        session()->flash('flash', [
            'type' => 'info',
            'title' => $label . ' export',
            'message' => 'Export functionality is not implemented yet.',
        ]);
    }

    #[Computed]
    public function products(): LengthAwarePaginator
    {
        return Product::query()
            ->with('category')
            ->when($this->search, fn ($q) => $q->where(function ($query) {
                $query->where('name', 'ilike', "%{$this->search}%")
                    ->orWhere('sku', 'ilike', "%{$this->search}%")
                    ->orWhere('barcode', 'ilike', "%{$this->search}%");
            }))
            ->when($this->categoryFilter, fn ($q) => $q->where('category_id', $this->categoryFilter))
            ->when($this->statusFilter !== '', fn ($q) => $q->where('is_active', $this->statusFilter === 'active'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[Computed]
    public function categories(): \Illuminate\Database\Eloquent\Collection
    {
        return ProductCategory::orderBy('sort_order')->get();
    }

    #[Computed]
    public function stats(): array
    {
        return [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'low_stock' => Product::where('track_inventory', true)
                ->whereColumn('stock_quantity', '<=', 'min_stock_level')
                ->count(),
        ];
    }

    public function render(): View
    {
        $editingProduct = null;

        if ($this->editingProductId) {
            $editingProduct = Product::with(['createdBy', 'updatedBy'])->find($this->editingProductId);
        }

        return view('livewire.general-setup.products.index', [
            'editingProduct' => $editingProduct,
        ])->layoutData([
            'pageTitle' => 'Products',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('products'),
        ]);
    }
}
