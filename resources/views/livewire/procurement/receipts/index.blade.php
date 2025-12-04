<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-4">
        <x-stat.card label="Total Receipts" :value="number_format($stats['totalReceipts'])" description="All time" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-inbox-arrow-down', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="This Month" :value="number_format($stats['thisMonth'])" description="Recent receipts" tone="info">
            <x-slot:icon>
                @svg('heroicon-o-calendar', 'h-5 w-5 text-sky-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Complete" :value="number_format($stats['complete'])" description="Fully received" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-check-circle', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Partial" :value="number_format($stats['partial'])" description="Partially received" tone="warning">
            <x-slot:icon>
                @svg('heroicon-o-exclamation-circle', 'h-5 w-5 text-amber-500')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Receipts Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-wrap items-center justify-between gap-3">
                {{-- Left: Search --}}
                <div class="relative">
                    @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search receipts..."
                        class="h-10 w-64 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                </div>

                {{-- Right: Selection, Filters, Export --}}
                <div class="flex items-center gap-2">
                    {{-- Selection Summary --}}
                    <x-table.selection-summary
                    :count="count($selectedItems)"
                    :total="count($receipts)"
                    description="receipts selected"
                    select-all-action="selectAll"
                    select-page-action="selectPage"
                    deselect-action="deselectAll"
                    :show-delete="false"
                    />

                    {{-- Dynamic Filters --}}
                <x-table.dynamic-filters :filters="[
                    'status' => [
                        'label' => 'Status',
                        'options' => [
                            'all' => 'All status',
                            'complete' => 'Complete',
                            'partial' => 'Partial',
                        ],
                        'selected' => $statusFilter,
                        'default' => 'all',
                        'onSelect' => 'setStatusFilter',
                    ],
                ]" />

                <x-table.export-dropdown aria-label="Export receipts" />
                </div>
            </div>
        </div>

        @if ($receipts->count() > 0)
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
                            <th class="px-5 py-3">REFERENCE</th>
                            <th class="px-5 py-3">PO REFERENCE</th>
                            <th class="px-5 py-3">SUPPLIER</th>
                            <th class="px-5 py-3">ITEMS</th>
                            <th class="px-5 py-3">STATUS</th>
                            <th class="px-5 py-3">RECEIVED</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($receipts as $receipt)
                            @php
                                $isSelected = in_array($receipt->id, $selectedItems);
                                $statusColors = [
                                    'complete' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                    'partial' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                                ];
                            @endphp
                            <tr class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5 {{ $isSelected ? 'bg-slate-50 dark:bg-white/5' : '' }}">
                                <td class="whitespace-nowrap px-5 py-4" wire:click.stop>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model.live="selectedItems" value="{{ $receipt->id }}"
                                            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10 dark:checked:bg-white dark:checked:text-slate-900">
                                    </label>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $receipt->reference }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $receipt->po_reference }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $receipt->supplier_name }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $receipt->items_count }} items
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $statusColors[$receipt->status] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ ucfirst($receipt->status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $receipt->received_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-inbox-arrow-down', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No receipts found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                    @if ($search || $statusFilter !== 'all')
                        Try adjusting your search or filters
                    @else
                        Goods receipts will appear here when orders are received
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
