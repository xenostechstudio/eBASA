<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2">
        <x-stat.card label="Total Expenses" :value="'Rp ' . number_format($stats['totalExpenses'] / 1000000, 0) . 'M'" description="This period" tone="warning">
            <x-slot:icon>@svg('heroicon-o-receipt-percent', 'h-5 w-5 text-amber-500')</x-slot:icon>
        </x-stat.card>
        <x-stat.card label="Categories" :value="number_format($stats['categories'])" description="Expense types" tone="neutral">
            <x-slot:icon>@svg('heroicon-o-tag', 'h-5 w-5 text-slate-500')</x-slot:icon>
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
                        <th class="px-5 py-3">CATEGORY</th>
                        <th class="px-5 py-3">AMOUNT</th>
                        <th class="px-5 py-3">PERCENTAGE</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                    @foreach ($data as $row)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                            <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $row['category'] }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">Rp {{ number_format($row['amount'], 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-24 rounded-full bg-slate-200 dark:bg-white/10">
                                        <div class="h-2 rounded-full bg-amber-500" style="width: {{ $row['percentage'] }}%"></div>
                                    </div>
                                    <span class="text-sm text-slate-600 dark:text-white/70">{{ $row['percentage'] }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
