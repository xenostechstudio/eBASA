<div class="space-y-6">
    {{-- Branch Notice --}}
    @if (!$activeBranch)
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/30 dark:bg-amber-500/10">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-exclamation-triangle', 'h-5 w-5 text-amber-600 dark:text-amber-400')
                <p class="text-sm text-amber-800 dark:text-amber-200">Please select a branch to manage products.</p>
            </div>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-5">
        <x-stat.card label="Total Products" :value="number_format($stats['totalProducts'])" :description="$activeBranch ? 'In ' . $activeBranch->name : 'All branches'" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-cube', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Available" :value="number_format($stats['available'])" description="Available to sell" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-check-circle', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Unavailable" :value="number_format($stats['unavailable'])" description="Inactive or hidden" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-x-circle', 'h-5 w-5 text-slate-400')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Low Stock" :value="number_format($stats['lowStock'])" description="Below minimum level" tone="warning">
            <x-slot:icon>
                @svg('heroicon-o-exclamation-triangle', 'h-5 w-5 text-amber-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Out of Stock" :value="number_format($stats['outOfStock'])" description="Needs restocking" tone="danger">
            <x-slot:icon>
                @svg('heroicon-o-archive-box-x-mark', 'h-5 w-5 text-rose-500')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        {{-- Filters & Actions --}}
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-wrap items-center justify-between gap-3">
                {{-- Search --}}
                <div class="relative">
                    @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products, SKU, barcode..."
                        class="h-10 w-64 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                </div>

                <div class="flex items-center gap-2">
                    {{-- Selection Summary --}}
                    <x-table.selection-summary
                        :count="count($selectedItems)"
                        :total="$branchProducts->total()"
                        description="products selected"
                        select-all-action="selectAll"
                        select-page-action="selectPage"
                        deselect-action="deselectAll"
                        delete-action="deleteSelected"
                        delete-label="Remove from branch"
                        :show-delete="true"
                    />

                    {{-- Dynamic Filters --}}
                    <x-table.dynamic-filters :filters="[
                        'category' => [
                            'label' => 'Category',
                            'options' => array_merge(['all' => 'All categories'], $categories->pluck('name', 'id')->toArray()),
                            'selected' => $categoryFilter,
                            'default' => 'all',
                            'onSelect' => 'setCategoryFilter',
                        ],
                        'status' => [
                            'label' => 'Availability',
                            'options' => [
                                'all' => 'All status',
                                'available' => 'Available',
                                'unavailable' => 'Unavailable',
                            ],
                            'selected' => $statusFilter,
                            'default' => 'all',
                            'onSelect' => 'setStatusFilter',
                        ],
                        'stock' => [
                            'label' => 'Stock status',
                            'options' => [
                                'all' => 'All stock',
                                'in_stock' => 'In Stock',
                                'low_stock' => 'Low Stock',
                                'out_of_stock' => 'Out of Stock',
                            ],
                            'selected' => $stockFilter,
                            'default' => 'all',
                            'onSelect' => 'setStockFilter',
                        ],
                    ]" />

                    {{-- Export --}}
                    <x-table.export-dropdown method="export" aria-label="Export branch products" />

                    {{-- Add Product --}}
                    <a href="{{ route('inventory.catalog.products.create') }}" wire:navigate
                        class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        <span>Add Product</span>
                    </a>
                </div>
            </div>
        </div>

        @if ($branchProducts->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/50 dark:border-white/10 dark:bg-white/5">
                        <th class="w-12 px-4 py-3">
                            <input type="checkbox" wire:model.live="selectPage" wire:click="toggleSelectPage"
                                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10">
                        </th>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="setSort('name')" class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">
                                Product
                                @if ($sortField === 'name')
                                    @svg($sortDirection === 'asc' ? 'heroicon-o-chevron-up' : 'heroicon-o-chevron-down', 'h-3 w-3')
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="setSort('sku')" class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">
                                SKU
                                @if ($sortField === 'sku')
                                    @svg($sortDirection === 'asc' ? 'heroicon-o-chevron-up' : 'heroicon-o-chevron-down', 'h-3 w-3')
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">Category</span>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="setSort('selling_price')" class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">
                                Price
                                @if ($sortField === 'selling_price')
                                    @svg($sortDirection === 'asc' ? 'heroicon-o-chevron-up' : 'heroicon-o-chevron-down', 'h-3 w-3')
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="setSort('stock_quantity')" class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">
                                Stock
                                @if ($sortField === 'stock_quantity')
                                    @svg($sortDirection === 'asc' ? 'heroicon-o-chevron-up' : 'heroicon-o-chevron-down', 'h-3 w-3')
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">Status</span>
                        </th>
                        <th class="w-12 px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                    @forelse ($branchProducts as $branchProduct)
                        <tr wire:click="goToProduct({{ $branchProduct->id }})"
                            class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5 {{ in_array((string)$branchProduct->id, $selectedItems) ? 'bg-slate-50 dark:bg-white/5' : '' }}">
                            <td class="px-4 py-3" wire:click.stop>
                                <input type="checkbox" wire:model.live="selectedItems" value="{{ $branchProduct->id }}"
                                    class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10">
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($branchProduct->is_featured)
                                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-500/20">
                                            @svg('heroicon-o-star', 'h-4 w-4 text-amber-600 dark:text-amber-400')
                                        </span>
                                    @else
                                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 dark:bg-white/10">
                                            @svg('heroicon-o-cube', 'h-4 w-4 text-slate-400 dark:text-white/40')
                                        </span>
                                    @endif
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $branchProduct->product->name ?? '-' }}</p>
                                        @if ($branchProduct->product->barcode)
                                            <p class="text-xs text-slate-400 dark:text-white/40">{{ $branchProduct->product->barcode }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-sm text-slate-600 dark:text-white/70">{{ $branchProduct->product->sku ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($branchProduct->product->category)
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700 dark:bg-white/10 dark:text-white/70">
                                        {{ $branchProduct->product->category->name }}
                                    </span>
                                @else
                                    <span class="text-sm text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-white">
                                        Rp {{ number_format($branchProduct->selling_price ?? $branchProduct->product->selling_price ?? 0, 0, ',', '.') }}
                                    </p>
                                    @if ($branchProduct->selling_price && $branchProduct->selling_price != $branchProduct->product->selling_price)
                                        <p class="text-xs text-slate-400 line-through">Rp {{ number_format($branchProduct->product->selling_price ?? 0, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $stockStatus = 'in_stock';
                                    if ($branchProduct->stock_quantity <= 0) {
                                        $stockStatus = 'out_of_stock';
                                    } elseif ($branchProduct->stock_quantity <= $branchProduct->min_stock_level) {
                                        $stockStatus = 'low_stock';
                                    }
                                @endphp

                                @if ($stockStatus === 'out_of_stock')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2 py-0.5 text-xs font-medium text-rose-700 dark:bg-rose-500/20 dark:text-rose-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                        Out of Stock
                                    </span>
                                @elseif ($stockStatus === 'low_stock')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        {{ $branchProduct->stock_quantity }} left
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-sm text-slate-600 dark:text-white/70">
                                        {{ $branchProduct->stock_quantity }} units
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($branchProduct->is_available)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Available
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 dark:bg-white/10 dark:text-white/60">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Unavailable
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3" wire:click.stop>
                                <a href="{{ route('inventory.catalog.products.edit', $branchProduct) }}" wire:navigate
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white">
                                    @svg('heroicon-o-pencil-square', 'h-4 w-4')
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    @svg('heroicon-o-cube', 'h-10 w-10 text-slate-300 dark:text-white/20')
                                    <p class="mt-3 text-sm font-medium text-slate-900 dark:text-white">No products found</p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-white/50">
                                        @if ($hasActiveFilters)
                                            Try adjusting your filters or search term.
                                        @else
                                            Get started by adding a product to this branch.
                                        @endif
                                    </p>
                                    @if (!$hasActiveFilters)
                                        <a href="{{ route('inventory.catalog.products.create') }}" wire:navigate
                                            class="mt-4 inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900">
                                            @svg('heroicon-o-plus', 'h-4 w-4')
                                            Add Product
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <x-table.pagination :paginator="$branchProducts" :per-page-options="[15, 25, 50, 100]" />
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-cube', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-900 dark:text-white">No products found</p>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/50">
                    @if ($hasActiveFilters)
                        Try adjusting your filters or search term.
                    @else
                        Get started by adding a product to this branch.
                    @endif
                </p>
                @if (!$hasActiveFilters)
                    <a href="{{ route('inventory.catalog.products.create') }}" wire:navigate
                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900">
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        Add Product
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
