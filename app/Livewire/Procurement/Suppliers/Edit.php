<?php

namespace App\Livewire\Procurement\Suppliers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Support\ProcurementNavigation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.portal-sidebar')]
class Edit extends Component
{
    use WithPagination;

    public Supplier $supplier;

    /**
     * @var array<string, mixed>
     */
    public array $form = [];

    // Relation manager state
    public string $ordersSearch = '';
    public string $ordersStatusFilter = 'all';
    public string $ordersSortField = 'order_date';
    public string $ordersSortDirection = 'desc';

    // Products tab state
    public string $productsSearch = '';
    public bool $showAddProductModal = false;
    public ?int $selectedProductId = null;
    public ?float $supplierPrice = null;
    public ?string $supplierSku = null;
    public ?int $leadTimeDays = null;
    public int $minOrderQty = 1;

    public function mount(Supplier $supplier): void
    {
        $this->supplier = $supplier;

        $this->form = [
            'name' => $this->supplier->name,
            'code' => $this->supplier->code,
            'contact_name' => $this->supplier->contact_name,
            'email' => $this->supplier->email,
            'phone' => $this->supplier->phone,
            'tax_number' => $this->supplier->tax_number,
            'address' => $this->supplier->address,
            'payment_terms' => $this->supplier->payment_terms,
            'is_active' => $this->supplier->is_active,
            'notes' => $this->supplier->notes,
        ];
    }

    public function save(): void
    {
        $validated = $this->validate([
            'form.name' => ['required', 'string', 'max:255'],
            'form.code' => ['required', 'string', 'max:50', 'unique:suppliers,code,' . $this->supplier->id],
            'form.contact_name' => ['nullable', 'string', 'max:255'],
            'form.email' => ['nullable', 'email', 'max:255'],
            'form.phone' => ['nullable', 'string', 'max:50'],
            'form.tax_number' => ['nullable', 'string', 'max:50'],
            'form.address' => ['nullable', 'string'],
            'form.payment_terms' => ['nullable', 'integer', 'min:0'],
            'form.is_active' => ['boolean'],
            'form.notes' => ['nullable', 'string'],
        ]);

        $this->supplier->update($validated['form']);

        session()->flash('status', 'Supplier updated successfully');

        $this->dispatch('notify', message: 'Supplier updated successfully');
    }

    // ─────────────────────────────────────────────────────────────
    // Orders Relation Manager
    // ─────────────────────────────────────────────────────────────

    public function setOrdersSort(string $field): void
    {
        if ($this->ordersSortField === $field) {
            $this->ordersSortDirection = $this->ordersSortDirection === 'asc' ? 'desc' : 'asc';
            return;
        }
        $this->ordersSortField = $field;
        $this->ordersSortDirection = 'asc';
    }

    public function setOrdersStatusFilter(string $status): void
    {
        $this->ordersStatusFilter = $status;
        $this->resetPage('ordersPage');
    }

    #[Computed]
    public function orders()
    {
        return $this->supplier->purchaseOrders()
            ->with('warehouse')
            ->when($this->ordersSearch, fn ($q) => $q->where('reference', 'like', "%{$this->ordersSearch}%"))
            ->when($this->ordersStatusFilter !== 'all', fn ($q) => $q->where('status', $this->ordersStatusFilter))
            ->orderBy($this->ordersSortField, $this->ordersSortDirection)
            ->paginate(5, pageName: 'ordersPage');
    }

    public function createOrderForSupplier(): void
    {
        $this->redirect(route('procurement.orders.create', ['supplier' => $this->supplier->id]), navigate: true);
    }

    // ─────────────────────────────────────────────────────────────
    // Products Relation Manager
    // ─────────────────────────────────────────────────────────────

    #[Computed]
    public function supplierProducts()
    {
        return $this->supplier->products()
            ->when($this->productsSearch, fn ($q) => $q->where('name', 'like', "%{$this->productsSearch}%"))
            ->orderBy('name')
            ->paginate(10, pageName: 'productsPage');
    }

    #[Computed]
    public function availableProducts()
    {
        $existingIds = $this->supplier->products()->pluck('products.id');

        return Product::whereNotIn('id', $existingIds)
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(50)
            ->get();
    }

    public function openAddProductModal(): void
    {
        $this->reset(['selectedProductId', 'supplierPrice', 'supplierSku', 'leadTimeDays', 'minOrderQty']);
        $this->minOrderQty = 1;
        $this->showAddProductModal = true;
    }

    public function addProduct(): void
    {
        $this->validate([
            'selectedProductId' => ['required', 'exists:products,id'],
            'supplierPrice' => ['nullable', 'numeric', 'min:0'],
            'supplierSku' => ['nullable', 'string', 'max:100'],
            'leadTimeDays' => ['nullable', 'integer', 'min:0'],
            'minOrderQty' => ['required', 'integer', 'min:1'],
        ]);

        $this->supplier->products()->attach($this->selectedProductId, [
            'supplier_price' => $this->supplierPrice,
            'supplier_sku' => $this->supplierSku,
            'lead_time_days' => $this->leadTimeDays,
            'min_order_qty' => $this->minOrderQty,
        ]);

        $this->showAddProductModal = false;
        unset($this->supplierProducts, $this->availableProducts);
        $this->dispatch('notify', message: 'Product added to supplier');
    }

    public function removeProduct(int $productId): void
    {
        $this->supplier->products()->detach($productId);
        unset($this->supplierProducts, $this->availableProducts);
        $this->dispatch('notify', message: 'Product removed from supplier');
    }

    public function togglePreferred(int $productId): void
    {
        $pivot = $this->supplier->products()->where('products.id', $productId)->first()?->pivot;
        if ($pivot) {
            $this->supplier->products()->updateExistingPivot($productId, [
                'is_preferred' => !$pivot->is_preferred,
            ]);
            unset($this->supplierProducts);
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Stats
    // ─────────────────────────────────────────────────────────────

    #[Computed]
    public function stats(): array
    {
        return [
            'lifetimeSpend' => $this->supplier->purchaseOrders()
                ->whereIn('status', ['approved', 'partially_received', 'received'])
                ->sum('total'),
            'ordersCount' => $this->supplier->purchaseOrders()->count(),
            'openOrders' => $this->supplier->purchaseOrders()
                ->whereIn('status', ['approved', 'partially_received'])
                ->count(),
            'productsCount' => $this->supplier->products()->count(),
        ];
    }

    public function render(): View
    {
        return view('livewire.procurement.suppliers.edit')->layoutData([
            'pageTitle' => 'Edit Supplier',
            'pageTagline' => 'Procurement',
            'activeModule' => 'procurement',
            'navLinks' => ProcurementNavigation::links('suppliers'),
        ]);
    }
}
