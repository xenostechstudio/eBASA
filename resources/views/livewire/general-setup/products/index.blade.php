<div class="space-y-6">
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'" :title="$flash['title'] ?? null">
            {{ $flash['message'] ?? '' }}
        </x-alert>
    @endif

    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-3">
        <x-stat.card
            label="Total Products"
            :value="number_format($this->stats['total'])"
            description="Products in catalog"
            tone="neutral"
        >
            <x-slot:icon>
                @svg('heroicon-o-cube', 'h-5 w-5 text-sky-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card
            label="Active"
            :value="number_format($this->stats['active'])"
            description="Sellable products"
            tone="success"
        >
            <x-slot:icon>
                @svg('heroicon-o-check-badge', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card
            label="Low Stock"
            :value="number_format($this->stats['low_stock'])"
            description="Need restock soon"
            tone="warning"
        >
            <x-slot:icon>
                @svg('heroicon-o-exclamation-triangle', 'h-5 w-5 text-amber-500')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Products Table --}}
    <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">All Products</h2>
                    <p class="text-xs text-slate-500 dark:text-white/60">Manage your product catalog</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Search --}}
                    <div class="relative">
                        @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search products..."
                            class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                        >
                    </div>

                    {{-- Dynamic Filters --}}
                    <x-table.dynamic-filters :filters="[
                        'status' => [
                            'label' => 'Status',
                            'options' => [
                                '' => 'All status',
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ],
                            'selected' => $statusFilter,
                            'default' => '',
                            'onSelect' => 'setStatusFilter',
                        ],
                        'category' => [
                            'label' => 'Category',
                            'options' => array_merge(
                                ['' => 'All categories'],
                                $this->categories->pluck('name', 'id')->toArray()
                            ),
                            'selected' => (string) $categoryFilter,
                            'default' => '',
                            'onSelect' => 'setCategoryFilter',
                        ],
                    ]" />

                    <x-table.export-dropdown aria-label="Export products" />

                    {{-- Add Product --}}
                    <x-button.primary
                        type="button"
                        wire:click="openCreateModal"
                        class="h-10 rounded-xl"
                    >
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        <span>Add Product</span>
                    </x-button.primary>
                </div>
            </div>
        </div>

        @php
            $statusLabel = null;
            $statusClasses = '';

            if ($statusFilter === 'active') {
                $statusLabel = 'Status: Active';
                $statusClasses = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300';
            } elseif ($statusFilter === 'inactive') {
                $statusLabel = 'Status: Inactive';
                $statusClasses = 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-white/80';
            }

            $categoryLabel = null;
            $categoryClasses = '';

            if ($categoryFilter) {
                $category = $this->categories->firstWhere('id', $categoryFilter);

                if ($category) {
                    $categoryLabel = 'Category: ' . $category->name;
                    $categoryClasses = 'bg-sky-50 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300';
                }
            }

            $productsCount = $this->products->total();
        @endphp

        @if ($statusLabel || $categoryLabel)
            <div
                class="border-b border-slate-100 bg-slate-50/70 px-5 py-2 text-xs text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-white/70">
                <div class="flex flex-wrap items-center gap-2">
                    @if ($statusLabel)
                        <div
                            class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-medium {{ $statusClasses }}">
                            <span>{{ $statusLabel }} ({{ $productsCount }})</span>
                            <button
                                type="button"
                                wire:click="setStatusFilter('')"
                                class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20"
                                aria-label="Reset status filter"
                            >
                                @svg('heroicon-o-x-mark', 'h-3 w-3')
                            </button>
                        </div>
                    @endif

                    @if ($categoryLabel)
                        <div
                            class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-medium {{ $categoryClasses }}">
                            <span>{{ $categoryLabel }} ({{ $productsCount }})</span>
                            <button
                                type="button"
                                wire:click="setCategoryFilter('')"
                                class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20"
                                aria-label="Reset category filter"
                            >
                                @svg('heroicon-o-x-mark', 'h-3 w-3')
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if ($this->products->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/60">
                            <th class="px-5 py-3">
                                <button wire:click="sortBy('sku')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    SKU
                                    @if ($sortField === 'sku')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">
                                <button wire:click="sortBy('name')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    PRODUCT
                                    @if ($sortField === 'name')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">CATEGORY</th>
                            <th class="px-5 py-3 text-right">
                                <button wire:click="sortBy('selling_price')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white ml-auto">
                                    PRICE
                                    @if ($sortField === 'selling_price')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3 text-right">
                                <button wire:click="sortBy('stock_quantity')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white ml-auto">
                                    STOCK
                                    @if ($sortField === 'stock_quantity')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($this->products as $product)
                            <tr
                                class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5"
                                wire:click="openEditModal({{ $product->id }})"
                            >
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="font-mono text-sm text-slate-600 dark:text-white/70">{{ $product->sku }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 dark:bg-white/10">
                                            @svg('heroicon-o-cube', 'h-5 w-5 text-slate-400')
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-900 dark:text-white">{{ $product->name }}</p>
                                            @if ($product->barcode)
                                                <p class="text-xs text-slate-500 dark:text-white/50">{{ $product->barcode }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($product->category)
                                        <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium"
                                            style="background-color: {{ $product->category->color }}20; color: {{ $product->category->color }}">
                                            {{ $product->category->name }}
                                        </span>
                                    @else
                                        <span class="text-sm text-slate-400 dark:text-white/40">-</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <span class="text-sm font-semibold text-slate-900 dark:text-white">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                                    <p class="text-xs text-slate-500 dark:text-white/50">Cost: Rp {{ number_format($product->cost_price, 0, ',', '.') }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    @if ($product->track_inventory)
                                        <span class="text-sm font-medium {{ $product->isLowStock() ? 'text-amber-600 dark:text-amber-400' : 'text-slate-900 dark:text-white' }}">
                                            {{ number_format($product->stock_quantity) }}
                                        </span>
                                        @if ($product->isLowStock())
                                            <p class="text-xs text-amber-600 dark:text-amber-400">Low stock</p>
                                        @endif
                                    @else
                                        <span class="text-sm text-slate-400 dark:text-white/40">N/A</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($product->is_active)
                                        <span class="inline-flex items-center rounded-lg bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 dark:bg-white/10 dark:text-white/60">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <x-table.pagination :paginator="$this->products" :per-page-options="$perPageOptions" />
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-cube', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No products found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                    @if ($search || $categoryFilter || $statusFilter)
                        Try adjusting your search or filters
                    @else
                        Get started by adding your first product
                    @endif
                </p>
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="cancelDelete">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-slate-800">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20">
                        @svg('heroicon-o-exclamation-triangle', 'h-6 w-6 text-red-600 dark:text-red-400')
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Delete Product</h3>
                        <p class="text-sm text-slate-500 dark:text-white/60">Are you sure you want to delete this product?</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="cancelDelete"
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-white dark:hover:bg-white/5">
                        Cancel
                    </button>
                    <button wire:click="deleteProduct"
                        class="rounded-xl bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Create/Edit Product Modal --}}
    @if ($showCreateModal || $showEditModal)
        @php $isEditing = ! is_null($editingProductId); @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            {{-- Background overlay --}}
            <div wire:click="closeModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity dark:bg-slate-950/70"></div>

            {{-- Modal panel --}}
            <div class="relative z-10 w-full max-w-4xl max-h-[calc(100vh-4rem)] flex flex-col overflow-hidden rounded-3xl bg-white text-slate-900 shadow-2xl dark:bg-slate-900 dark:text-white">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ $isEditing ? 'Edit Product' : 'Add New Product' }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-white/60">
                        {{ $isEditing ? 'Update product information' : 'Create a new product' }}
                    </p>
                </div>

                @include('livewire.general-setup.products._form', [
                    'isEditing' => $isEditing,
                    'editingProduct' => $editingProduct ?? null,
                ])
            </div>
        </div>
    @endif
</div>
