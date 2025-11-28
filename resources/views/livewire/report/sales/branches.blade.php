<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-3">
        <x-stat.card label="Branches" :value="number_format($stats['totalBranches'])" description="Active locations" tone="neutral">
            <x-slot:icon>@svg('heroicon-o-building-storefront', 'h-5 w-5 text-slate-500')</x-slot:icon>
        </x-stat.card>
        <x-stat.card label="Total Revenue" :value="'Rp ' . number_format($stats['totalRevenue'] / 1000000, 0) . 'M'" description="All branches" tone="success">
            <x-slot:icon>@svg('heroicon-o-banknotes', 'h-5 w-5 text-emerald-500')</x-slot:icon>
        </x-stat.card>
        <x-stat.card label="Avg Growth" :value="number_format($stats['avgGrowth'], 1) . '%'" description="Month over month" tone="info">
            <x-slot:icon>@svg('heroicon-o-arrow-trending-up', 'h-5 w-5 text-sky-500')</x-slot:icon>
        </x-stat.card>
    </div>

    <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex items-center justify-end"><x-table.export-dropdown aria-label="Export report" /></div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/60">
                        <th class="px-5 py-3">BRANCH</th>
                        <th class="px-5 py-3">ORDERS</th>
                        <th class="px-5 py-3">REVENUE</th>
                        <th class="px-5 py-3">GROWTH</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                    @foreach ($data as $row)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                            <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $row['name'] }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">{{ number_format($row['orders']) }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-slate-900 dark:text-white">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="inline-flex items-center gap-1 text-sm font-medium {{ $row['growth'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    @svg($row['growth'] >= 0 ? 'heroicon-s-arrow-up' : 'heroicon-s-arrow-down', 'h-3 w-3')
                                    {{ abs($row['growth']) }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
