<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-banknotes', 'h-5 w-5 text-emerald-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Total Revenue</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">Rp {{ number_format($stats['totalRevenue'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">All time completed transactions</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-receipt-percent', 'h-5 w-5 text-sky-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Total Transactions</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['totalTransactions']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">All recorded transactions</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-calculator', 'h-5 w-5 text-amber-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Avg. Transaction</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">Rp {{ number_format($stats['avgTransaction'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Average transaction value</p>
        </div>
    </div>

    {{-- Reports Section --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Available Reports</h2>
        <p class="text-xs text-slate-500 dark:text-white/60">Generate and download transaction reports</p>

        <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div class="group rounded-xl border border-slate-200 p-4 transition hover:border-slate-300 hover:shadow-sm dark:border-white/10 dark:hover:border-white/20">
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400">
                        @svg('heroicon-o-document-chart-bar', 'h-5 w-5')
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-slate-900 dark:text-white">Daily Sales Report</h3>
                        <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Summary of daily transactions and revenue</p>
                        <button class="mt-3 text-xs font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">Generate Report →</button>
                    </div>
                </div>
            </div>

            <div class="group rounded-xl border border-slate-200 p-4 transition hover:border-slate-300 hover:shadow-sm dark:border-white/10 dark:hover:border-white/20">
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-100 text-sky-600 dark:bg-sky-500/20 dark:text-sky-400">
                        @svg('heroicon-o-calendar-days', 'h-5 w-5')
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-slate-900 dark:text-white">Monthly Summary</h3>
                        <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Monthly breakdown by branch and payment method</p>
                        <button class="mt-3 text-xs font-medium text-sky-600 hover:text-sky-700 dark:text-sky-400">Generate Report →</button>
                    </div>
                </div>
            </div>

            <div class="group rounded-xl border border-slate-200 p-4 transition hover:border-slate-300 hover:shadow-sm dark:border-white/10 dark:hover:border-white/20">
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400">
                        @svg('heroicon-o-user-group', 'h-5 w-5')
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-slate-900 dark:text-white">Cashier Performance</h3>
                        <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Individual cashier transaction metrics</p>
                        <button class="mt-3 text-xs font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400">Generate Report →</button>
                    </div>
                </div>
            </div>

            <div class="group rounded-xl border border-slate-200 p-4 transition hover:border-slate-300 hover:shadow-sm dark:border-white/10 dark:hover:border-white/20">
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-500/20 dark:text-purple-400">
                        @svg('heroicon-o-credit-card', 'h-5 w-5')
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-slate-900 dark:text-white">Payment Methods</h3>
                        <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Breakdown by payment type and trends</p>
                        <button class="mt-3 text-xs font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400">Generate Report →</button>
                    </div>
                </div>
            </div>

            <div class="group rounded-xl border border-slate-200 p-4 transition hover:border-slate-300 hover:shadow-sm dark:border-white/10 dark:hover:border-white/20">
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400">
                        @svg('heroicon-o-arrow-uturn-left', 'h-5 w-5')
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-slate-900 dark:text-white">Refund Analysis</h3>
                        <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Refund patterns and reasons</p>
                        <button class="mt-3 text-xs font-medium text-red-600 hover:text-red-700 dark:text-red-400">Generate Report →</button>
                    </div>
                </div>
            </div>

            <div class="group rounded-xl border border-slate-200 p-4 transition hover:border-slate-300 hover:shadow-sm dark:border-white/10 dark:hover:border-white/20">
                <div class="flex items-start gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-white/60">
                        @svg('heroicon-o-building-storefront', 'h-5 w-5')
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-slate-900 dark:text-white">Branch Comparison</h3>
                        <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Compare performance across branches</p>
                        <button class="mt-3 text-xs font-medium text-slate-600 hover:text-slate-700 dark:text-white/60">Generate Report →</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
