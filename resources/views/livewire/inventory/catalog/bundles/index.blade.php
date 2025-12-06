<div class="space-y-6">
    {{-- Branch Notice --}}
    @if (!$activeBranch)
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/30 dark:bg-amber-500/10">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-exclamation-triangle', 'h-5 w-5 text-amber-600 dark:text-amber-400')
                <p class="text-sm text-amber-800 dark:text-amber-200">Please select a branch to manage bundles.</p>
            </div>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-4">
        <x-stat.card label="Total Bundles" :value="number_format($stats['totalBundles'])" :description="$activeBranch ? 'In ' . $activeBranch->name : 'All branches'" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-gift', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Active" :value="number_format($stats['active'])" description="Active bundles" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-check-circle', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Inactive" :value="number_format($stats['inactive'])" description="Inactive bundles" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-x-circle', 'h-5 w-5 text-slate-400')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Currently Valid" :value="number_format($stats['valid'])" description="Within validity" tone="warning">
            <x-slot:icon>
                @svg('heroicon-o-calendar-days', 'h-5 w-5 text-blue-500')
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search bundles..."
                        class="h-10 w-64 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                </div>

                <div class="flex items-center gap-2">
                    {{-- Selection Summary --}}
                    <x-table.selection-summary
                        :count="count($selectedItems)"
                        :total="$bundles->total()"
                        description="bundles selected"
                        select-all-action="selectAll"
                        select-page-action="selectPage"
                        deselect-action="deselectAll"
                        delete-action="deleteSelected"
                        delete-label="Delete bundles"
                        :show-delete="true"
                    />

                    {{-- Dynamic Filters --}}
                    <x-table.dynamic-filters :filters="[
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
                        'validity' => [
                            'label' => 'Validity',
                            'options' => [
                                'all' => 'All validity',
                                'valid' => 'Currently Valid',
                                'expired' => 'Expired',
                                'upcoming' => 'Upcoming',
                            ],
                            'selected' => $validityFilter,
                            'default' => 'all',
                            'onSelect' => 'setValidityFilter',
                        ],
                    ]" />

                    {{-- Export --}}
                    <x-table.export-dropdown method="export" aria-label="Export bundles" />

                    {{-- Add Bundle --}}
                    <a href="{{ route('inventory.catalog.bundles.create') }}" wire:navigate
                        class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        <span>Add Bundle</span>
                    </a>
                </div>
            </div>
        </div>

        @if ($bundles->count() > 0)
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
                                Bundle
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
                        <th class="px-4 py-3 text-center">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">Items</span>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="setSort('bundle_price')" class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">
                                Price
                                @if ($sortField === 'bundle_price')
                                    @svg($sortDirection === 'asc' ? 'heroicon-o-chevron-up' : 'heroicon-o-chevron-down', 'h-3 w-3')
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">Validity</span>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">Status</span>
                        </th>
                        <th class="w-12 px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                    @foreach ($bundles as $bundle)
                        <tr wire:click="goToBundle({{ $bundle->id }})"
                            class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5 {{ in_array((string)$bundle->id, $selectedItems) ? 'bg-slate-50 dark:bg-white/5' : '' }}">
                            <td class="px-4 py-3" wire:click.stop>
                                <input type="checkbox" wire:model.live="selectedItems" value="{{ $bundle->id }}"
                                    class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10">
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-500/20">
                                        @svg('heroicon-o-gift', 'h-4 w-4 text-violet-600 dark:text-violet-400')
                                    </span>
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $bundle->name }}</p>
                                        @if ($bundle->description)
                                            <p class="text-xs text-slate-400 dark:text-white/40 line-clamp-1">{{ $bundle->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-sm text-slate-600 dark:text-white/70">{{ $bundle->sku ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700 dark:bg-white/10 dark:text-white/70">
                                    {{ $bundle->items_count }} items
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-medium text-slate-900 dark:text-white">Rp {{ number_format($bundle->bundle_price ?? 0, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $now = now();
                                    $isValid = (!$bundle->valid_from || $bundle->valid_from <= $now) && (!$bundle->valid_until || $bundle->valid_until >= $now);
                                    $isExpired = $bundle->valid_until && $bundle->valid_until < $now;
                                    $isUpcoming = $bundle->valid_from && $bundle->valid_from > $now;
                                @endphp

                                @if ($isExpired)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2 py-0.5 text-xs font-medium text-rose-700 dark:bg-rose-500/20 dark:text-rose-400">
                                        Expired
                                    </span>
                                @elseif ($isUpcoming)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-500/20 dark:text-blue-400">
                                        Starts {{ $bundle->valid_from->format('M d') }}
                                    </span>
                                @elseif ($bundle->valid_until)
                                    <span class="inline-flex items-center gap-1 text-xs text-slate-500 dark:text-white/50">
                                        Until {{ $bundle->valid_until->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 dark:text-white/40">No expiry</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($bundle->is_active)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 dark:bg-white/10 dark:text-white/60">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3" wire:click.stop>
                                <a href="{{ route('inventory.catalog.bundles.edit', $bundle) }}" wire:navigate
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white">
                                    @svg('heroicon-o-pencil-square', 'h-4 w-4')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <x-table.pagination :paginator="$bundles" :per-page-options="[15, 25, 50, 100]" />
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-gift', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-900 dark:text-white">No bundles found</p>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/50">
                    @if ($hasActiveFilters)
                        Try adjusting your filters or search term.
                    @else
                        Get started by creating your first bundle.
                    @endif
                </p>
                @if (!$hasActiveFilters)
                    <a href="{{ route('inventory.catalog.bundles.create') }}" wire:navigate
                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900">
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        Create Bundle
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
