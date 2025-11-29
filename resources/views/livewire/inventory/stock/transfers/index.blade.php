<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-4">
        <x-stat.card label="Total Transfers" :value="number_format($stats['totalTransfers'])" description="All time" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-arrows-right-left', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Pending" :value="number_format($stats['pending'])" description="Awaiting dispatch" tone="warning">
            <x-slot:icon>
                @svg('heroicon-o-clock', 'h-5 w-5 text-amber-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="In Transit" :value="number_format($stats['inTransit'])" description="On the way" tone="info">
            <x-slot:icon>
                @svg('heroicon-o-truck', 'h-5 w-5 text-sky-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Completed" :value="number_format($stats['completed'])" description="Successfully delivered" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-check-circle', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Transfers Table --}}
    <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-wrap items-center justify-end gap-3">
                {{-- Selection Summary --}}
                <x-table.selection-summary
                    :count="count($selectedItems)"
                    :total="count($transfers)"
                    description="transfers selected"
                    select-all-action="selectAll"
                    select-page-action="selectPage"
                    deselect-action="deselectAll"
                    delete-action="deleteSelected"
                />

                {{-- Search --}}
                <div class="relative">
                    @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search transfers..."
                        class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                </div>

                {{-- Dynamic Filters --}}
                <x-table.dynamic-filters :filters="[
                    'status' => [
                        'label' => 'Status',
                        'options' => [
                            'all' => 'All status',
                            'pending' => 'Pending',
                            'in_transit' => 'In Transit',
                            'completed' => 'Completed',
                        ],
                        'selected' => $statusFilter,
                        'default' => 'all',
                        'onSelect' => 'setStatusFilter',
                    ],
                ]" />

                <x-table.export-dropdown aria-label="Export transfers" />

                {{-- New Transfer --}}
                <a href="{{ route('inventory.stock.transfers.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg:white dark:text-slate-900 dark:hover:bg-white/90">
                    @svg('heroicon-o-plus', 'h-4 w-4')
                    <span>New Transfer</span>
                </a>
            </div>
        </div>

        @if ($transfers->count() > 0)
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
                            <th class="px-5 py-3">FROM</th>
                            <th class="px-5 py-3">TO</th>
                            <th class="px-5 py-3">ITEMS</th>
                            <th class="px-5 py-3">STATUS</th>
                            <th class="px-5 py-3">DATE</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($transfers as $transfer)
                            @php
                                $isSelected = in_array($transfer->id, $selectedItems);
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                                    'in_transit' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400',
                                    'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                ];
                            @endphp
                            <tr wire:click="goToTransfer({{ $transfer->id }})" class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5 {{ $isSelected ? 'bg-slate-50 dark:bg-white/5' : '' }}">
                                <td class="whitespace-nowrap px-5 py-4" wire:click.stop>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model.live="selectedItems" value="{{ $transfer->id }}"
                                            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10 dark:checked:bg-white dark:checked:text-slate-900">
                                    </label>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $transfer->reference }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $transfer->from_warehouse }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $transfer->to_warehouse }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $transfer->items_count }} items
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $statusColors[$transfer->status] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ str($transfer->status)->headline() }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $transfer->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-arrows-right-left', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No transfers found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                    @if ($search || $statusFilter !== 'all')
                        Try adjusting your search or filters
                    @else
                        Create your first stock transfer
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
