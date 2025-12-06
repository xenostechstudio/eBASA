<div class="space-y-6">
    {{-- Branch Notice --}}
    @if (!$activeBranch)
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/30 dark:bg-amber-500/10">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-exclamation-triangle', 'h-5 w-5 text-amber-500')
                <p class="text-sm text-amber-700 dark:text-amber-400">Please select a branch to view branch-specific stock levels.</p>
            </div>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-4">
        <x-stat.card label="Total Products" :value="number_format($stats['totalProducts'])" :description="$activeBranch ? 'In ' . $activeBranch->name : 'All branches'" tone="neutral">
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
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-wrap items-center justify-between gap-3">
                {{-- Left: Search --}}
                <div class="relative">
                    @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products..."
                        class="h-10 w-64 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                </div>

                {{-- Right: Selection, Filters, Export, Adjustment --}}
                <div class="flex items-center gap-2">
                    {{-- Selection Summary --}}
                    <x-table.selection-summary
                        :count="count($selectedItems)"
                        :total="$branchProducts->total()"
                        description="products selected"
                        select-all-action="selectAll"
                        select-page-action="selectPage"
                        deselect-action="deselectAll"
                        :show-delete="false"
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

                    {{-- New Adjustment --}}
                    <a href="{{ route('inventory.stock.adjustments.create') }}"
                        class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                        @svg('heroicon-o-adjustments-horizontal', 'h-4 w-4')
                        <span>Adjust Stock</span>
                    </a>
                </div>
            </div>
        </div>

        @if ($branchProducts->count() > 0)
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
                            <th class="px-5 py-3">
                                <button wire:click="setSort('min_stock_level')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    MIN LEVEL
                                    @if ($sortField === 'min_stock_level')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($branchProducts as $bp)
                            @php
                                $isSelected = in_array($bp->id, $selectedItems);
                                $minLevel = $bp->min_stock_level ?? 0;
                                $stockStatus = match(true) {
                                    $bp->stock_quantity <= 0 => 'out_of_stock',
                                    $bp->stock_quantity <= $minLevel => 'low_stock',
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
                            <tr wire:click="goToProduct({{ $bp->id }})" class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5 {{ $isSelected ? 'bg-slate-50 dark:bg-white/5' : '' }}">
                                <td class="whitespace-nowrap px-5 py-4" wire:click.stop>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model.live="selectedItems" value="{{ $bp->id }}"
                                            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10 dark:checked:bg-white dark:checked:text-slate-900">
                                    </label>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $bp->product->name }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $bp->product->sku ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $bp->product->category?->name ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="text-sm font-medium {{ $stockStatus === 'out_of_stock' ? 'text-rose-600 dark:text-rose-400' : ($stockStatus === 'low_stock' ? 'text-amber-600 dark:text-amber-400' : 'text-slate-900 dark:text-white') }}">
                                        {{ number_format($bp->stock_quantity) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ number_format($bp->min_stock_level) }}
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

            <x-table.pagination :paginator="$branchProducts" :per-page-options="[15, 30, 50, 100]" />
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-archive-box', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No products found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                    @if ($search || $categoryFilter !== 'all' || $stockFilter !== 'all')
                        Try adjusting your search or filters
                    @elseif (!$activeBranch)
                        Select a branch to view stock levels
                    @else
                        Add products to this branch to track stock levels
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
