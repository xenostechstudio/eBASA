<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-calculator', 'h-5 w-5 text-sky-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Total Counted</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['totalCounted']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Cash counts recorded</p>
        </div>
        <div class="rounded-2xl border border-amber-200 bg-amber-50/50 p-6 dark:border-amber-500/20 dark:bg-amber-500/10">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-exclamation-triangle', 'h-5 w-5 text-amber-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">Discrepancies</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['discrepancies']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Counts with differences</p>
        </div>
    </div>

    {{-- Cash Counts Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Cash Count History</h2>
                <p class="text-xs text-slate-500 dark:text-white/60">Cash reconciliation records from closed shifts</p>
            </div>
        </div>

        @if ($cashCounts->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:text-white/60">
                            <th class="px-5 py-3">Cashier</th>
                            <th class="px-5 py-3">Branch</th>
                            <th class="px-5 py-3 text-right">Expected</th>
                            <th class="px-5 py-3 text-right">Actual</th>
                            <th class="px-5 py-3 text-right">Difference</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Counted At</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($cashCounts as $count)
                            @php
                                $difference = ($count->actual_cash ?? 0) - ($count->expected_cash ?? 0);
                                $hasDiscrepancy = $difference != 0;
                            @endphp
                            <tr class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $count->cashier?->name ?? '-' }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $count->branch?->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-slate-600 dark:text-white/70">
                                    Rp {{ number_format($count->expected_cash ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-medium text-slate-900 dark:text-white">
                                    Rp {{ number_format($count->actual_cash ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <span class="text-sm font-medium {{ $difference >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $difference >= 0 ? '+' : '' }}Rp {{ number_format($difference, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($hasDiscrepancy)
                                        <span class="inline-flex items-center rounded-lg bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
                                            Discrepancy
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-lg bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                            Balanced
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $count->closed_at?->format('d M Y H:i') ?? '-' }}
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
                {{ $cashCounts->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-calculator', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No cash counts found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">Cash counts will appear here when shifts are closed</p>
            </div>
        @endif
    </div>
</div>
