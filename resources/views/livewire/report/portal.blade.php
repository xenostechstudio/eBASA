<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-4">
        <x-stat.card label="Total Revenue" :value="'Rp ' . number_format($stats['totalRevenue'] / 1000000, 0) . 'M'" description="This month" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-banknotes', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Total Orders" :value="number_format($stats['totalOrders'])" description="This month" tone="info">
            <x-slot:icon>
                @svg('heroicon-o-shopping-cart', 'h-5 w-5 text-sky-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Avg. Order Value" :value="'Rp ' . number_format($stats['avgOrderValue'] / 1000, 0) . 'K'" description="Per transaction" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-calculator', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Growth Rate" :value="$stats['growthRate'] . '%'" description="vs last month" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-arrow-trending-up', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Quick Links --}}
    <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-6">
        @foreach ($quickLinks as $link)
            <a href="{{ $link['href'] }}"
                class="group flex flex-col items-center rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm transition hover:border-slate-300 hover:shadow-md dark:border-white/10 dark:bg-white/5 dark:hover:border-white/20">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition group-hover:bg-slate-200 dark:bg-white/10 dark:text-white dark:group-hover:bg-white/20">
                    @svg($link['icon'], 'h-6 w-6')
                </span>
                <p class="mt-3 text-sm font-medium text-slate-900 dark:text-white">{{ $link['label'] }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-white/50">{{ $link['description'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Recent Reports --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/40">Recent</p>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Generated Reports</h3>
                </div>
            </div>
            <div class="mt-4 space-y-3">
                @foreach ($recentReports as $report)
                    <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-white/10 dark:bg-slate-950/40">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-slate-200 text-slate-600 dark:bg-white/10 dark:text-white">
                                @svg('heroicon-o-document-chart-bar', 'h-5 w-5')
                            </span>
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white">{{ $report['name'] }}</p>
                                <p class="text-sm text-slate-500 dark:text-white/50">{{ $report['type'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-500 dark:text-white/50">{{ $report['generated'] }}</span>
                            <button class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-200 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white">
                                @svg('heroicon-o-arrow-down-tray', 'h-4 w-4')
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Report Categories --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/40">Categories</p>
            <h3 class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">Report Types</h3>
            <div class="mt-4 space-y-3">
                <a href="{{ route('reports.sales.daily') }}" class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 transition hover:bg-slate-100 dark:border-white/10 dark:bg-slate-950/40 dark:hover:bg-white/10">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400">
                        @svg('heroicon-o-currency-dollar', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">Sales Reports</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">Revenue & transactions</p>
                    </div>
                </a>
                <a href="{{ route('reports.inventory.stock') }}" class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 transition hover:bg-slate-100 dark:border-white/10 dark:bg-slate-950/40 dark:hover:bg-white/10">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-sky-100 text-sky-600 dark:bg-sky-500/20 dark:text-sky-400">
                        @svg('heroicon-o-archive-box', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">Inventory Reports</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">Stock & movements</p>
                    </div>
                </a>
                <a href="{{ route('reports.financial.revenue') }}" class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 transition hover:bg-slate-100 dark:border-white/10 dark:bg-slate-950/40 dark:hover:bg-white/10">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400">
                        @svg('heroicon-o-banknotes', 'h-5 w-5')
                    </span>
                    <div>
                        <p class="font-medium text-slate-900 dark:text-white">Financial Reports</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">Revenue & expenses</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
