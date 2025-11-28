<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-4">
        <x-stat.card label="Total Lists" :value="number_format($stats['totalLists'])" description="Price lists" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-currency-dollar', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Active" :value="number_format($stats['active'])" description="Currently in use" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-check-circle', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Inactive" :value="number_format($stats['inactive'])" description="Not in use" tone="warning">
            <x-slot:icon>
                @svg('heroicon-o-pause-circle', 'h-5 w-5 text-amber-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Default" :value="number_format($stats['default'])" description="Primary price list" tone="info">
            <x-slot:icon>
                @svg('heroicon-o-star', 'h-5 w-5 text-sky-500')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Price Lists Table --}}
    <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-wrap items-center justify-end gap-3">
                {{-- Selection Summary --}}
                <x-table.selection-summary
                    :count="count($selectedItems)"
                    :total="count($priceLists)"
                    description="price lists selected"
                    select-all-action="selectAll"
                    select-page-action="selectPage"
                    deselect-action="deselectAll"
                    delete-action="deleteSelected"
                />

                {{-- Search --}}
                <div class="relative">
                    @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search price lists..."
                        class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                </div>

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
                ]" />

                <x-table.export-dropdown aria-label="Export price lists" />

                {{-- Create Price List --}}
                <button wire:click="openCreateModal"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                    @svg('heroicon-o-plus', 'h-4 w-4')
                    <span>Create Price List</span>
                </button>
            </div>
        </div>

        @if ($priceLists->count() > 0)
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
                                    NAME
                                    @if ($sortField === 'name')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">CODE</th>
                            <th class="px-5 py-3">PRODUCTS</th>
                            <th class="px-5 py-3">VALIDITY</th>
                            <th class="px-5 py-3">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($priceLists as $priceList)
                            @php $isSelected = in_array($priceList->id, $selectedItems); @endphp
                            <tr class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5 {{ $isSelected ? 'bg-slate-50 dark:bg-white/5' : '' }}">
                                <td class="whitespace-nowrap px-5 py-4" wire:click.stop>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model.live="selectedItems" value="{{ $priceList->id }}"
                                            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10 dark:checked:bg-white dark:checked:text-slate-900">
                                    </label>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $priceList->name }}</p>
                                        @if ($priceList->is_default)
                                            <span class="inline-flex items-center rounded-lg bg-sky-100 px-2 py-0.5 text-xs font-medium text-sky-700 dark:bg-sky-500/20 dark:text-sky-400">
                                                Default
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $priceList->code }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $priceList->products_count }} products
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $priceList->valid_from->format('M d, Y') }}
                                    @if ($priceList->valid_until)
                                        — {{ $priceList->valid_until->format('M d, Y') }}
                                    @else
                                        — No expiry
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $priceList->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white/60' }}">
                                        {{ $priceList->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-currency-dollar', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No price lists found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                    @if ($search || $statusFilter !== 'all')
                        Try adjusting your search or filters
                    @else
                        Create your first price list
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
