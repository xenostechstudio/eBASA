<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-3">
        <x-stat.card label="Total Revenue" :value="'Rp ' . number_format($stats['totalRevenue'] / 1000000, 0) . 'M'" description="Year to date" tone="success">
            <x-slot:icon>@svg('heroicon-o-banknotes', 'h-5 w-5 text-emerald-500')</x-slot:icon>
        </x-stat.card>
        <x-stat.card label="Target" :value="'Rp ' . number_format($stats['totalTarget'] / 1000000, 0) . 'M'" description="Annual goal" tone="info">
            <x-slot:icon>@svg('heroicon-o-flag', 'h-5 w-5 text-sky-500')</x-slot:icon>
        </x-stat.card>
        <x-stat.card label="Achievement" :value="$stats['achievement'] . '%'" description="Of target" tone="success">
            <x-slot:icon>@svg('heroicon-o-trophy', 'h-5 w-5 text-amber-500')</x-slot:icon>
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
                        <th class="px-5 py-3">MONTH</th>
                        <th class="px-5 py-3">REVENUE</th>
                        <th class="px-5 py-3">TARGET</th>
                        <th class="px-5 py-3">ACHIEVEMENT</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                    @foreach ($data as $row)
                        @php $achievement = round($row['revenue'] / $row['target'] * 100, 1); @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                            <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $row['month'] }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">Rp {{ number_format($row['target'], 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-5 py-4"><span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $achievement >= 100 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400' }}">{{ $achievement }}%</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
