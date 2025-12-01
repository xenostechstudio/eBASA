<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'" :title="$flash['title'] ?? null">
            {{ $flash['message'] ?? '' }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Stats Cards --}}
        <div class="grid gap-4 md:grid-cols-3">
            <x-stat.card label="Total Items" :value="number_format($stats['total'])" description="Payroll components" tone="neutral">
                <x-slot:icon>
                    @svg('heroicon-o-rectangle-stack', 'h-5 w-5 text-sky-500')
                </x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Earnings" :value="number_format($stats['earnings'])" description="Allowances & bonuses" tone="success">
                <x-slot:icon>
                    @svg('heroicon-o-arrow-trending-up', 'h-5 w-5 text-emerald-500')
                </x-slot:icon>
            </x-stat.card>

            <x-stat.card label="Deductions" :value="number_format($stats['deductions'])" description="Taxes & contributions" tone="warning">
                <x-slot:icon>
                    @svg('heroicon-o-arrow-trending-down', 'h-5 w-5 text-amber-500')
                </x-slot:icon>
            </x-stat.card>
        </div>

        {{-- Table Card --}}
        <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Payroll Items</h2>
                        <p class="text-xs text-slate-500 dark:text-white/60">
                            Manage earnings and deductions for payroll
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        {{-- Search --}}
                        <div class="relative">
                            @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search items..."
                                class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                        </div>

                        {{-- Filters --}}
                        <x-table.dynamic-filters :filters="[
                            'type' => [
                                'label' => 'Type',
                                'options' => [
                                    '' => 'All Types',
                                    'earning' => 'Earning',
                                    'deduction' => 'Deduction',
                                ],
                                'selected' => $typeFilter,
                                'default' => '',
                                'onSelect' => 'setTypeFilter',
                            ],
                            'category' => [
                                'label' => 'Category',
                                'options' => array_merge(['' => 'All Categories'], $categories),
                                'selected' => $categoryFilter,
                                'default' => '',
                                'onSelect' => 'setCategoryFilter',
                            ],
                        ]" />

                        <x-table.export-dropdown aria-label="Export payroll items" />

                        {{-- Add Item --}}
                        <a
                            href="{{ route('hr.payroll-items.create') }}"
                            class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                        >
                            @svg('heroicon-o-plus', 'h-4 w-4')
                            <span>Add Item</span>
                        </a>
                    </div>
                </div>
            </div>

            @if ($items->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/60">
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
                                <th class="px-5 py-3">CATEGORY</th>
                                <th class="px-5 py-3 text-right">DEFAULT AMOUNT</th>
                                <th class="px-5 py-3">STATUS</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                            @foreach ($items as $item)
                                <tr class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5"
                                    onclick="window.location='{{ route('hr.payroll-items.edit', $item) }}'">
                                    <td class="whitespace-nowrap px-5 py-4 font-mono text-xs text-slate-600 dark:text-white/70">
                                        {{ $item->code }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $item->name }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @if ($item->type === 'earning')
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">
                                                Earning
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-[11px] font-medium text-rose-700 dark:bg-rose-500/20 dark:text-rose-300">
                                                Deduction
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                        {{ $item->category_label }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-medium text-slate-900 dark:text-white">
                                        Rp {{ number_format($item->default_amount, 0, ',', '.') }}
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
                                        <div class="flex items-center justify-end gap-1">
                                            <a
                                                href="{{ route('hr.payroll-items.edit', $item) }}"
                                                onclick="event.stopPropagation()"
                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white"
                                                title="Edit">
                                                @svg('heroicon-o-pencil', 'h-4 w-4')
                                            </a>
                                            <button type="button"
                                                wire:click.stop="confirmDelete({{ $item->id }})"
                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-400"
                                                title="Delete">
                                                @svg('heroicon-o-trash', 'h-4 w-4')
                                            </button>
                                        </div>
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
                    <p class="mt-1 text-xs text-slate-400 dark:text-white/40">Create your first payroll item to get started</p>
                </div>
            @endif
        </div>
    </div>

    <x-modal.confirm-delete :show="$showDeleteConfirm" title="Delete payroll item"
        description="This action cannot be undone. This will permanently delete the payroll item." :item-name="$deletingItemName"
        confirm-action="deleteItem" cancel-action="cancelDelete" confirm-text="Delete item" />
</div>
