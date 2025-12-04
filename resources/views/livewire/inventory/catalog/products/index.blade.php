<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-4">
        <x-stat.card label="Total Products" :value="number_format($stats['totalProducts'])" description="In catalog" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-shopping-bag', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Active" :value="number_format($stats['active'])" description="Available for sale" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-check-circle', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Inactive" :value="number_format($stats['inactive'])" description="Not available" tone="warning">
            <x-slot:icon>
                @svg('heroicon-o-pause-circle', 'h-5 w-5 text-amber-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Categories" :value="number_format($stats['categories'])" description="Product groups" tone="info">
            <x-slot:icon>
                @svg('heroicon-o-rectangle-stack', 'h-5 w-5 text-sky-500')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Products Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-wrap items-center justify-between gap-3">
                {{-- Left: Search --}}
                <div class="relative">
                    @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products..."
                        class="h-10 w-64 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                </div>

                {{-- Right: Selection, Filters, Export, Add --}}
                <div class="flex items-center gap-2">
                    {{-- Selection Summary --}}
                    <x-table.selection-summary
                    :count="count($selectedItems)"
                    :total="$products->total()"
                    description="products selected"
                    select-all-action="selectAll"
                    select-page-action="selectPage"
                    deselect-action="deselectAll"
                    delete-action="deleteSelected"
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
                        'label' => 'Status',
                        'options' => [
                            'all' => 'All status',
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                        ],
                        'selected' => $statusFilter,
                        'default' => 'all',
                        'onSelect' => 'setStatusFilter',
                    ],
                ]" />

                <x-table.export-dropdown aria-label="Export products" />

                {{-- Add Product --}}
                <a href="{{ route('general-setup.products.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                    @svg('heroicon-o-plus', 'h-4 w-4')
                    <span>Add Product</span>
                    </a>
                </div>
            </div>
        </div>

        @if ($products->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/60">
                            <th class="w-12 px-5 py-3">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:click="toggleSelectPage" @checked($selectPage)
                                        class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10 dark:checked:bg-white dark:checked:text-slate-900">
                                </label>
                            </th>
                            <th class="px-5 py-3">
                                <button wire:click="setSort('name')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    PRODUCT
                                    @if ($sortField === 'name')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">
                                <button wire:click="setSort('sku')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    SKU
                                    @if ($sortField === 'sku')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">CATEGORY</th>
                            <th class="px-5 py-3">
                                <button wire:click="setSort('selling_price')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    PRICE
                                    @if ($sortField === 'selling_price')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">
                                <button wire:click="setSort('stock_quantity')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
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
                        @foreach ($products as $product)
                            @php $isSelected = in_array($product->id, $selectedItems); @endphp
                            <tr wire:click="goToProduct({{ $product->id }})" class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5 {{ $isSelected ? 'bg-slate-50 dark:bg-white/5' : '' }}">
                                <td class="whitespace-nowrap px-5 py-4" wire:click.stop>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model.live="selectedItems" value="{{ $product->id }}"
                                            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10 dark:checked:bg-white dark:checked:text-slate-900">
                                    </label>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $product->name }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $product->sku ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $product->category?->name ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-slate-900 dark:text-white">
                                    Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ number_format($product->stock_quantity ?? 0) }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $product->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white/60' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-table.pagination :paginator="$products" :per-page-options="[10, 25, 50, 100]" />
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-shopping-bag', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No products found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                    @if ($search || $categoryFilter !== 'all' || $statusFilter !== 'all')
                        Try adjusting your search or filters
                    @else
                        Add your first product to the catalog
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
