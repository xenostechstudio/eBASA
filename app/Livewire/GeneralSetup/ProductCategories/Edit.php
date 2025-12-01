<?php

namespace App\Livewire\GeneralSetup\ProductCategories;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Support\GeneralSetupNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
#[Title('Edit Category')]
class Edit extends Component
{
    use WithPagination;

    public ?int $categoryId = null;
    public ?ProductCategory $editingCategory = null;

    public string $name = '';
    public string $description = '';
    public int $sort_order = 0;
    public string $color = '#6366f1';
    public bool $is_active = true;

    // Product relation manager
    #[Url]
    public string $productSearch = '';

    public string $productStatusFilter = '';

    public function mount(ProductCategory $category): void
    {
        $this->categoryId = $category->id;
        $this->editingCategory = $category->load(['createdBy', 'updatedBy']);

        $this->name = $category->name;
        $this->description = $category->description ?? '';
        $this->sort_order = $category->sort_order ?? 0;
        $this->color = $category->color ?? '#6366f1';
        $this->is_active = (bool) $category->is_active;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:product_categories,name,' . $this->categoryId,
            'description' => 'nullable|string|max:500',
            'sort_order' => 'integer|min:0',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ];
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->where('category_id', $this->categoryId)
            ->when($this->productSearch, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->productSearch . '%')
                        ->orWhere('sku', 'like', '%' . $this->productSearch . '%');
                });
            })
            ->when($this->productStatusFilter !== '', function ($query) {
                $query->where('is_active', $this->productStatusFilter === 'active');
            })
            ->orderBy('name')
            ->paginate(10);
    }

    #[Computed]
    public function productStats()
    {
        return [
            'total' => Product::where('category_id', $this->categoryId)->count(),
            'active' => Product::where('category_id', $this->categoryId)->where('is_active', true)->count(),
            'inactive' => Product::where('category_id', $this->categoryId)->where('is_active', false)->count(),
        ];
    }

    public function setProductStatusFilter(string $value): void
    {
        $this->productStatusFilter = $value;
        $this->resetPage();
    }

    public function save(): void
    {
        $this->validate();

        if (! $this->categoryId) {
            return;
        }

        $category = ProductCategory::find($this->categoryId);

        if (! $category) {
            return;
        }

        $category->update([
            'name' => $this->name,
            'description' => $this->description ?: null,
            'sort_order' => $this->sort_order,
            'color' => $this->color ?: null,
            'is_active' => $this->is_active,
        ]);

        $this->editingCategory = $category->fresh(['createdBy', 'updatedBy']);

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Category updated',
            'message' => 'Category updated successfully.',
        ]);
    }

    public function removeProduct(int $productId): void
    {
        $product = Product::find($productId);

        if ($product && $product->category_id === $this->categoryId) {
            $product->update(['category_id' => null]);

            session()->flash('flash', [
                'type' => 'success',
                'title' => 'Product removed',
                'message' => 'Product removed from this category.',
            ]);
        }
    }

    public function cancel(): void
    {
        $this->redirectRoute('general-setup.product-categories.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.general-setup.product-categories.edit', [
            'editingCategory' => $this->editingCategory,
        ])->layoutData([
            'pageTitle' => 'Edit Category',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('product-categories'),
        ]);
    }
}
