<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-4">
        <x-stat.card label="Total Products" :value="number_format($stats['totalProducts'])" description="In catalog" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-cube', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="In Stock" :value="number_format($stats['inStock'])" description="Available items" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-check-circle', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Low Stock" :value="number_format($stats['lowStock'])" description="Below reorder level" tone="warning">
            <x-slot:icon>
                @svg('heroicon-o-exclamation-triangle', 'h-5 w-5 text-amber-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Out of Stock" :value="number_format($stats['outOfStock'])" description="Needs restocking" tone="danger">
            <x-slot:icon>
                @svg('heroicon-o-x-circle', 'h-5 w-5 text-rose-500')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Stock Levels Table --}}
    <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-wrap items-center justify-end gap-3">
                {{-- Selection Summary --}}
                <x-table.selection-summary
                    :count="count($selectedItems)"
                    :total="$products->total()"
                    description="products selected"
                    select-all-action="selectAll"
                    select-page-action="selectPage"
                    deselect-action="deselectAll"
                    :show-delete="false"
                />

                {{-- Search --}}
                <div class="relative">
                    @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products..."
                        class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                </div>

                {{-- Dynamic Filters --}}
                <x-table.dynamic-filters :filters="[
                    'warehouse' => [
                        'label' => 'Warehouse',
                        'options' => array_merge(['all' => 'All warehouses'], $warehouses->pluck('name', 'id')->toArray()),
                        'selected' => $warehouseFilter,
                        'default' => 'all',
                        'onSelect' => 'setWarehouseFilter',
                    ],
                    'stock' => [
                        'label' => 'Stock Status',
                        'options' => [
                            'all' => 'All status',
                            'in_stock' => 'In Stock',
                            'low_stock' => 'Low Stock',
                            'out_of_stock' => 'Out of Stock',
                        ],
                        'selected' => $stockFilter,
                        'default' => 'all',
                        'onSelect' => 'setStockFilter',
                    ],
                ]" />

                <x-table.export-dropdown aria-label="Export stock levels" />
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
                                <button wire:click="setSort('stock_quantity')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    QUANTITY
                                    @if ($sortField === 'stock_quantity')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">REORDER LEVEL</th>
                            <th class="px-5 py-3">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($products as $product)
                            @php
                                $isSelected = in_array($product->id, $selectedItems);
                                $stockStatus = match(true) {
                                    $product->stock_quantity <= 0 => 'out_of_stock',
                                    $product->stock_quantity <= $product->reorder_level => 'low_stock',
                                    default => 'in_stock',
                                };
                                $statusColors = [
                                    'in_stock' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                    'low_stock' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                                    'out_of_stock' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
                                ];
                                $statusLabels = [
                                    'in_stock' => 'In Stock',
                                    'low_stock' => 'Low Stock',
                                    'out_of_stock' => 'Out of Stock',
                                ];
                            @endphp
                            <tr class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5 {{ $isSelected ? 'bg-slate-50 dark:bg-white/5' : '' }}">
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
                                    {{ number_format($product->stock_quantity ?? 0) }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ number_format($product->reorder_level ?? 0) }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $statusColors[$stockStatus] }}">
                                        {{ $statusLabels[$stockStatus] }}
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
                @svg('heroicon-o-archive-box', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No products found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                    @if ($search || $warehouseFilter !== 'all' || $stockFilter !== 'all')
                        Try adjusting your search or filters
                    @else
                        Add products to track stock levels
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
