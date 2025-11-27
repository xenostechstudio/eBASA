<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-banknotes', 'h-5 w-5 text-emerald-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Total Settlements</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['totalSettlements']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">All time closed shifts</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-calendar', 'h-5 w-5 text-sky-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Today</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['todaySettlements']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Settlements today</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-currency-dollar', 'h-5 w-5 text-amber-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Cash Collected</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">Rp {{ number_format($stats['totalCashCollected'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Total cash from all settlements</p>
        </div>
    </div>

    {{-- Settlements Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Settlement History</h2>
                    <p class="text-xs text-slate-500 dark:text-white/60">Closed cashier shifts and cash reconciliation</p>
                </div>
                <div class="relative">
                    @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search cashier..."
                        class="h-10 w-64 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                    >
                </div>
            </div>
        </div>

        @if ($settlements->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:text-white/60">
                            <th class="px-5 py-3">Cashier</th>
                            <th class="px-5 py-3">Branch</th>
                            <th class="px-5 py-3">Shift Duration</th>
                            <th class="px-5 py-3 text-right">Expected Cash</th>
                            <th class="px-5 py-3 text-right">Actual Cash</th>
                            <th class="px-5 py-3 text-right">Difference</th>
                            <th class="px-5 py-3">Closed At</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($settlements as $settlement)
                            @php
                                $difference = ($settlement->actual_cash ?? 0) - ($settlement->expected_cash ?? 0);
                            @endphp
                            <tr class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $settlement->cashier?->name ?? '-' }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $settlement->branch?->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    @if ($settlement->opened_at && $settlement->closed_at)
                                        {{ $settlement->opened_at->diffForHumans($settlement->closed_at, true) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-slate-600 dark:text-white/70">
                                    Rp {{ number_format($settlement->expected_cash ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-medium text-slate-900 dark:text-white">
                                    Rp {{ number_format($settlement->actual_cash ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <span class="text-sm font-medium {{ $difference >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $difference >= 0 ? '+' : '' }}Rp {{ number_format($difference, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $settlement->closed_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <button class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white" title="View Details">
                                        @svg('heroicon-o-eye', 'h-4 w-4')
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4 dark:border-white/10">
                {{ $settlements->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-banknotes', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No settlements found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">Settlements will appear here when cashier shifts are closed</p>
            </div>
        @endif
    </div>
</div>
