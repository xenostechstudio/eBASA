<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-4">
        <x-stat.card label="Total Adjustments" :value="number_format($stats['totalAdjustments'])" description="All time" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-adjustments-horizontal', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Additions" :value="number_format($stats['additions'])" description="Stock increased" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-plus-circle', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Reductions" :value="number_format($stats['reductions'])" description="Stock decreased" tone="warning">
            <x-slot:icon>
                @svg('heroicon-o-minus-circle', 'h-5 w-5 text-amber-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="This Month" :value="number_format($stats['thisMonth'])" description="Recent adjustments" tone="info">
            <x-slot:icon>
                @svg('heroicon-o-calendar', 'h-5 w-5 text-sky-500')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Adjustments Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-wrap items-center justify-between gap-3">
                {{-- Left: Search --}}
                <div class="relative">
                    @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search adjustments..."
                        class="h-10 w-64 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                </div>

                {{-- Right: Selection, Filters, Export, Add --}}
                <div class="flex items-center gap-2">
                    {{-- Selection Summary --}}
                    <x-table.selection-summary
                    :count="count($selectedItems)"
                    :total="count($adjustments)"
                    description="adjustments selected"
                    select-all-action="selectAll"
                    select-page-action="selectPage"
                    deselect-action="deselectAll"
                    delete-action="deleteSelected"
                    />

                    {{-- Dynamic Filters --}}
                <x-table.dynamic-filters :filters="[
                    'warehouse' => [
                        'label' => 'Warehouse',
                        'options' => array_merge(['all' => 'All warehouses'], $warehouses->pluck('name', 'id')->toArray()),
                        'selected' => $warehouseFilter,
                        'default' => 'all',
                        'onSelect' => 'setWarehouseFilter',
                    ],
                    'type' => [
                        'label' => 'Type',
                        'options' => [
                            'all' => 'All types',
                            'addition' => 'Addition',
                            'reduction' => 'Reduction',
                        ],
                        'selected' => $typeFilter,
                        'default' => 'all',
                        'onSelect' => 'setTypeFilter',
                    ],
                ]" />

                <x-table.export-dropdown aria-label="Export adjustments" />

                {{-- Add Adjustment --}}
                <a href="{{ route('inventory.stock.adjustments.create') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                    @svg('heroicon-o-plus', 'h-4 w-4')
                    <span>New Adjustment</span>
                    </a>
                </div>
            </div>
        </div>

        @if ($adjustments->count() > 0)
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
                            <th class="px-5 py-3">WAREHOUSE</th>
                            <th class="px-5 py-3">TYPE</th>
                            <th class="px-5 py-3">QUANTITY</th>
                            <th class="px-5 py-3">REASON</th>
                            <th class="px-5 py-3">DATE</th>
                            <th class="px-5 py-3">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($adjustments as $adjustment)
                            @php
                                $isSelected = in_array($adjustment->id, $selectedItems);
                            @endphp
                            <tr wire:click="goToAdjustment({{ $adjustment->id }})" class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5 {{ $isSelected ? 'bg-slate-50 dark:bg-white/5' : '' }}">
                                <td class="whitespace-nowrap px-5 py-4" wire:click.stop>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model.live="selectedItems" value="{{ $adjustment->id }}"
                                            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10 dark:checked:bg-white dark:checked:text-slate-900">
                                    </label>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $adjustment->reference }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $adjustment->warehouse?->name }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $adjustment->type === 'addition' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400' }}">
                                        {{ ucfirst($adjustment->type) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm font-medium {{ $adjustment->type === 'addition' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                    {{ $adjustment->type === 'addition' ? '+' : '-' }}{{ number_format($adjustment->items->sum('quantity')) }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $adjustment->reason }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $adjustment->created_at->format('M d, Y') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @php
                                        $rawStatus = $adjustment->status;
                                        $status = $rawStatus instanceof \App\Enums\StockAdjustmentStatus
                                            ? $rawStatus->value
                                            : (string) $rawStatus;

                                        $statusLabel = $rawStatus instanceof \App\Enums\StockAdjustmentStatus
                                            ? $rawStatus->label()
                                            : (string) str($status)->headline();

                                        $statusClasses = [
                                            'draft' => 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-white/70',
                                            'on_process' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-300',
                                            'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300',
                                            'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300',
                                        ][$status] ?? 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-white/70';
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $statusClasses }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-adjustments-horizontal', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No adjustments found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                    @if ($search || $warehouseFilter !== 'all' || $typeFilter !== 'all')
                        Try adjusting your search or filters
                    @else
                        Create your first stock adjustment
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
