<?php

namespace App\Livewire\GeneralSetup\ProductCategories;

use App\Models\ProductCategory;
use App\Support\GeneralSetupNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal-sidebar')]
#[Title('Add Category')]
class Create extends Component
{
    public string $name = '';
    public string $description = '';
    public int $sort_order = 0;
    public string $color = '#6366f1';
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:product_categories,name',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'integer|min:0',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $category = ProductCategory::create([
            'name' => $this->name,
            'description' => $this->description ?: null,
            'sort_order' => $this->sort_order,
            'color' => $this->color ?: null,
            'is_active' => $this->is_active,
        ]);

        session()->flash('flash', [
            'type' => 'success',
            'title' => 'Category created',
            'message' => 'Category created successfully.',
        ]);

        $this->redirectRoute('general-setup.product-categories.edit', ['category' => $category->id], navigate: true);
    }

    public function cancel(): void
    {
        $this->redirectRoute('general-setup.product-categories.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.general-setup.product-categories.create')->layoutData([
            'pageTitle' => 'Add Category',
            'pageTagline' => 'General Setup',
            'activeModule' => 'general-setup',
            'navLinks' => GeneralSetupNavigation::links('product-categories'),
        ]);
    }
}
