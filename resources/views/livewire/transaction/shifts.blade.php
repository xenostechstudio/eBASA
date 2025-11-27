<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-6 dark:border-emerald-500/20 dark:bg-emerald-500/10">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-play-circle', 'h-5 w-5 text-emerald-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-400">Open Shifts</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['openShifts']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Currently active cashiers</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-stop-circle', 'h-5 w-5 text-sky-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Closed Today</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['closedToday']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Shifts closed today</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-clock', 'h-5 w-5 text-amber-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Total Shifts</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['totalShifts']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">All time shifts</p>
        </div>
    </div>

    {{-- Shifts Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Cashier Shifts</h2>
                    <p class="text-xs text-slate-500 dark:text-white/60">All cashier shift records</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search cashier..."
                            class="h-10 w-64 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                        >
                    </div>
                    <div class="relative">
                        @svg('heroicon-o-funnel', 'pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                        <select
                            wire:model.live="statusFilter"
                            class="h-10 appearance-none rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-8 text-sm text-slate-700 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white"
                        >
                            <option value="">All Status</option>
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                        </select>
                        @svg('heroicon-s-chevron-down', 'pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                    </div>
                </div>
            </div>
        </div>

        @if ($shifts->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:text-white/60">
                            <th class="px-5 py-3">Cashier</th>
                            <th class="px-5 py-3">Branch</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Opened At</th>
                            <th class="px-5 py-3">Closed At</th>
                            <th class="px-5 py-3 text-right">Opening Cash</th>
                            <th class="px-5 py-3 text-right">Transactions</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($shifts as $shift)
                            <tr class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $shift->cashier?->name ?? '-' }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $shift->branch?->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($shift->status === 'open')
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Open
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 dark:bg-white/10 dark:text-white/60">
                                            Closed
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $shift->opened_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $shift->closed_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-slate-600 dark:text-white/70">
                                    Rp {{ number_format($shift->opening_cash ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $shift->transactions_count ?? 0 }}
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
                {{ $shifts->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-clock', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No shifts found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">Cashier shifts will appear here</p>
            </div>
        @endif
    </div>
</div>
