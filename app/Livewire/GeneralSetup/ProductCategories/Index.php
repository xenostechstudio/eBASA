<?php

namespace App\Livewire\GeneralSetup\ProductCategories;

use App\Models\ProductCategory;
use App\Support\GeneralSetupNavigation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    // Modal state
    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public ?int $editingCategoryId = null;

    // Form fields
    public string $name = '';

    public string $description = '';

    public int $sort_order = 0;

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal(int $categoryId): void
    {
        $category = ProductCategory::find($categoryId);
        if ($category) {
            $this->editingCategoryId = $categoryId;
            $this->name = $category->name;
            $this->description = $category->description ?? '';
            $this->sort_order = $category->sort_order ?? 0;
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
        $this->editingCategoryId = null;
        $this->name = '';
        $this->description = '';
        $this->sort_order = 0;
        $this->resetValidation();
    }

    public function save(): void
    {
        $isEditing = ! is_null($this->editingCategoryId);

        $rules = [
            'name' => 'required|string|max:255|unique:product_categories,name' . ($isEditing ? ',' . $this->editingCategoryId : ''),
            'description' => 'nullable|string|max:500',
            'sort_order' => 'integer|min:0',
        ];

        $this->validate($rules);

        if ($isEditing) {
            $category = ProductCategory::find($this->editingCategoryId);
            if ($category) {
                $category->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'sort_order' => $this->sort_order,
                ]);
            }

            $flashMessage = 'Category updated successfully.';
            $flashTitle = 'Category updated';
        } else {
            ProductCategory::create([
                'name' => $this->name,
                'description' => $this->description,
                'sort_order' => $this->sort_order,
            ]);

            $flashMessage = 'Category created successfully.';
            $flashTitle = 'Category created';
        }

        $this->closeModal();

        session()->flash('flash', [
            'type' => 'success',
            'title' => $flashTitle,
            'message' => $flashMessage,
        ]);
    }

    public function deleteCategory(int $categoryId): void
    {
        ProductCategory::destroy($categoryId);

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Category deleted',
            'message' => 'Category deleted successfully.',
        ]);
    }

    public function render()
    {
        $categories = ProductCategory::query()
            ->withCount('products')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15);

        $stats = [
            'total' => ProductCategory::count(),
            'withProducts' => ProductCategory::has('products')->count(),
            'empty' => ProductCategory::doesntHave('products')->count(),
        ];

        return view('livewire.general-setup.product-categories.index', [
            'categories' => $categories,
            'stats' => $stats,
        ])->layoutData([
            'pageTitle' => 'Product Categories',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('product-categories'),
        ]);
    }
}
