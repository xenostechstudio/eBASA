<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-3">
        <x-stat.card
            label="Total Refunds"
            :value="number_format($stats['totalRefunds'])"
            description="All time refunded transactions"
            icon="heroicon-o-arrow-uturn-left"
            icon-color="text-red-500"
        />
        <x-stat.card
            label="Refund Amount"
            :value="'Rp ' . number_format($stats['totalRefundAmount'], 0, ',', '.')"
            description="Total refunded value"
            icon="heroicon-o-banknotes"
            icon-color="text-amber-500"
        />
        <x-stat.card
            label="Today"
            :value="number_format($stats['todayRefunds'])"
            description="Refunds processed today"
            icon="heroicon-o-calendar"
            icon-color="text-sky-500"
        />
    </div>

    {{-- Refunds Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Refund History</h2>
                    <p class="text-xs text-slate-500 dark:text-white/60">All refunded transactions</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Search --}}
                    <div class="relative">
                        @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search transaction..."
                            class="h-10 w-48 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                        >
                    </div>

                    {{-- Date From --}}
                    <input
                        type="date"
                        wire:model.live="dateFrom"
                        class="h-10 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white"
                        title="From date"
                    >

                    {{-- Date To --}}
                    <input
                        type="date"
                        wire:model.live="dateTo"
                        class="h-10 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white"
                        title="To date"
                    >

                    {{-- Clear Filters --}}
                    @if ($search || $dateFrom || $dateTo)
                        <button
                            wire:click="clearFilters"
                            class="inline-flex h-10 items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm text-slate-600 transition hover:bg-slate-100 dark:border-white/10 dark:bg-white/5 dark:text-white/70 dark:hover:bg-white/10"
                            title="Clear filters"
                        >
                            @svg('heroicon-o-x-mark', 'h-4 w-4')
                            <span class="hidden sm:inline">Clear</span>
                        </button>
                    @endif

                    {{-- Export --}}
                    <x-table.export-dropdown method="export" />
                </div>
            </div>
        </div>

        @if ($refunds->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:text-white/60">
                            <th class="px-5 py-3">Transaction Code</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Branch</th>
                            <th class="px-5 py-3">Cashier</th>
                            <th class="px-5 py-3 text-right">Amount</th>
                            <th class="px-5 py-3">Refunded At</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($refunds as $refund)
                            <tr wire:click="viewTransaction({{ $refund->id }})" class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="font-mono text-sm font-medium text-slate-900 dark:text-white">{{ $refund->transaction_code }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($refund->customer_name)
                                        <p class="text-sm text-slate-900 dark:text-white">{{ $refund->customer_name }}</p>
                                    @else
                                        <span class="text-sm text-slate-400 dark:text-white/40">Walk-in</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $refund->branch?->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $refund->cashier?->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <span class="text-sm font-semibold text-red-600 dark:text-red-400">-Rp {{ number_format($refund->total_amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $refund->updated_at->format('d M Y H:i') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4" wire:click.stop>
                                    <button wire:click="viewTransaction({{ $refund->id }})" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white" title="View Details">
                                        @svg('heroicon-o-eye', 'h-4 w-4')
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-table.pagination :paginator="$refunds" :per-page-options="$perPageOptions" />
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-arrow-uturn-left', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No refunds found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">Refunded transactions will appear here</p>
            </div>
        @endif
    </div>

    {{-- Transaction Detail Modal --}}
    <x-transaction-receipt-modal :transaction="$selectedTransaction" />
</div>
