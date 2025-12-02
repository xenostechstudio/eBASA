<div>
    @if (session()->has('status'))
        <x-alert type="success">{{ session('status') }}</x-alert>
    @endif
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'">{{ $flash['message'] ?? '' }}</x-alert>
    @endif

    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-stat.card label="Total Items" :value="number_format($stats['total'])" description="Payroll components" tone="neutral">
                <x-slot:icon>@svg('heroicon-o-rectangle-stack', 'h-5 w-5 text-slate-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Earnings" :value="number_format($stats['earnings'])" description="Allowances & bonuses" tone="success">
                <x-slot:icon>@svg('heroicon-o-arrow-trending-up', 'h-5 w-5 text-emerald-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Deductions" :value="number_format($stats['deductions'])" description="Taxes & contributions" tone="warning">
                <x-slot:icon>@svg('heroicon-o-arrow-trending-down', 'h-5 w-5 text-amber-500')</x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Active" :value="number_format(\App\Models\PayrollItem::where('is_active', true)->count())" description="Currently active" tone="info">
                <x-slot:icon>@svg('heroicon-o-check-circle', 'h-5 w-5 text-sky-500')</x-slot:icon>
            </x-stat.card>
        </div>

        {{-- Table Card --}}
        <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            {{-- Toolbar --}}
            <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    {{-- Left: Search --}}
                    <div class="relative">
                        @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search items..."
                            class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                    </div>

                    {{-- Right: Selection, Filters, Export, Add --}}
                    <div class="flex items-center gap-2">
                        {{-- Selection Summary --}}
                        <x-table.selection-summary
                            :count="count($selectedItems)"
                            :total="$items->total()"
                            description="items selected"
                            select-all-action="selectAllItems"
                            select-page-action="selectPage"
                            deselect-action="deselectAll"
                            delete-action="deleteSelected"
                        />

                        {{-- Dynamic Filters --}}
                        <x-table.dynamic-filters :filters="[
                            'type' => [
                                'label' => 'Type',
                                'options' => [
                                    'all' => 'All types',
                                    'earning' => 'Earning',
                                    'deduction' => 'Deduction',
                                ],
                                'selected' => $typeFilter ?: 'all',
                                'default' => 'all',
                                'onSelect' => 'setTypeFilter',
                            ],
                            'category' => [
                                'label' => 'Category',
                                'options' => array_merge(['all' => 'All categories'], $categories),
                                'selected' => $categoryFilter ?: 'all',
                                'default' => 'all',
                                'onSelect' => 'setCategoryFilter',
                            ],
                        ]" />

                        <x-table.export-dropdown aria-label="Export payroll items" />

                        {{-- Add Item --}}
                        <a href="{{ route('hr.payroll-items.create') }}"
                            class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                            @svg('heroicon-o-plus', 'h-4 w-4')
                            <span>Add Item</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Active Filters Pills --}}
            @php $hasActiveFilters = $typeFilter !== '' || $categoryFilter !== ''; @endphp
            @if ($hasActiveFilters)
                <div class="border-b border-slate-100 bg-slate-50/70 px-5 py-2 dark:border-white/10 dark:bg-white/5">
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($typeFilter !== '')
                            <span class="inline-flex items-center gap-2 rounded-full {{ $typeFilter === 'earning' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300' }} px-3 py-1 text-[11px] font-medium">
                                <span>Type: {{ str($typeFilter)->headline() }}</span>
                                <button type="button" wire:click="setTypeFilter('')" class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20">
                                    @svg('heroicon-o-x-mark', 'h-3 w-3')
                                </button>
                            </span>
                        @endif

                        @if ($categoryFilter !== '')
                            <span class="inline-flex items-center gap-2 rounded-full bg-sky-50 px-3 py-1 text-[11px] font-medium text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                                <span>Category: {{ $categories[$categoryFilter] ?? $categoryFilter }}</span>
                                <button type="button" wire:click="setCategoryFilter('')" class="inline-flex h-4 w-4 items-center justify-center rounded-full hover:bg-white/50 dark:hover:bg-white/20">
                                    @svg('heroicon-o-x-mark', 'h-3 w-3')
                                </button>
                            </span>
                        @endif

                        <button type="button" wire:click="setTypeFilter(''); $wire.setCategoryFilter('')" class="text-[11px] font-medium text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white/70">
                            Clear all
                        </button>
                    </div>
                </div>
            @endif

            {{-- Table --}}
            @if ($items->count() > 0)
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
                                    <button wire:click="sortBy('code')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                        CODE
                                        @if ($sortField === 'code')
                                            @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                        @endif
                                    </button>
                                </th>
                                <th class="px-5 py-3">
                                    <button wire:click="sortBy('name')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                        NAME
                                        @if ($sortField === 'name')
                                            @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                        @endif
                                    </button>
                                </th>
                                <th class="px-5 py-3">TYPE</th>
                                <th class="px-5 py-3">
                                    <div class="flex items-center gap-1">
                                        CATEGORY
                                        <span class="text-slate-400" title="Payroll item classification">@svg('heroicon-o-information-circle', 'h-3.5 w-3.5')</span>
                                    </div>
                                </th>
                                <th class="px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        DEFAULT AMOUNT
                                        <span class="text-slate-400" title="Default value, can be overridden per employee">@svg('heroicon-o-information-circle', 'h-3.5 w-3.5')</span>
                                    </div>
                                </th>
                                <th class="px-5 py-3">STATUS</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                            @foreach ($items as $item)
                                @php $isSelected = in_array($item->id, $selectedItems); @endphp
                                <tr
                                    wire:key="item-{{ $item->id }}"
                                    class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5 {{ $isSelected ? 'bg-slate-50 dark:bg-white/5' : '' }}"
                                    onclick="window.location='{{ route('hr.payroll-items.edit', $item) }}'"
                                >
                                    <td class="whitespace-nowrap px-5 py-4" wire:click.stop onclick="event.stopPropagation()">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model.live="selectedItems" value="{{ $item->id }}"
                                                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10 dark:checked:bg-white dark:checked:text-slate-900">
                                        </label>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 font-mono text-xs text-slate-600 dark:text-white/70">
                                        {{ $item->code }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $item->name }}</p>
                                        @if ($item->description)
                                            <p class="text-xs text-slate-500 dark:text-white/50 truncate max-w-xs">{{ $item->description }}</p>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @if ($item->type === 'earning')
                                            <span class="inline-flex items-center rounded-lg bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                                Earning
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-lg bg-rose-100 px-2 py-1 text-xs font-medium text-rose-700 dark:bg-rose-500/20 dark:text-rose-400">
                                                Deduction
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                        {{ $item->category_label }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right">
                                        @if ($item->default_amount > 0)
                                            <p class="font-medium text-slate-900 dark:text-white">Rp {{ number_format($item->default_amount, 0, ',', '.') }}</p>
                                        @else
                                            <span class="text-xs text-slate-400 dark:text-white/40">Variable</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @if ($item->is_active)
                                            <span class="inline-flex items-center rounded-lg bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 dark:bg-white/10 dark:text-white/60">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @svg('heroicon-o-chevron-right', 'h-4 w-4 text-slate-400')
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <x-table.pagination :paginator="$items" :per-page-options="$perPageOptions" />
            @else
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    @svg('heroicon-o-rectangle-stack', 'h-12 w-12 text-slate-300 dark:text-white/20')
                    <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No payroll items found</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                        @if ($search || $hasActiveFilters)
                            Try adjusting your search or filters
                        @else
                            Create your first payroll item to get started
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    <x-modal.confirm-delete :show="$showDeleteConfirm" title="Delete payroll item"
        description="This action cannot be undone. This will permanently delete the payroll item." :item-name="$deletingItemName"
        confirm-action="deleteItem" cancel-action="cancelDelete" confirm-text="Delete item" />
</div>
