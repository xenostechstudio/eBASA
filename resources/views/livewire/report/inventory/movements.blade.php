<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-3">
        <x-stat.card label="Stock In" :value="number_format($stats['totalIn'])" description="Units received" tone="success">
            <x-slot:icon>@svg('heroicon-o-arrow-down-tray', 'h-5 w-5 text-emerald-500')</x-slot:icon>
        </x-stat.card>
        <x-stat.card label="Stock Out" :value="number_format($stats['totalOut'])" description="Units sold/used" tone="warning">
            <x-slot:icon>@svg('heroicon-o-arrow-up-tray', 'h-5 w-5 text-amber-500')</x-slot:icon>
        </x-stat.card>
        <x-stat.card label="Transfers" :value="number_format($stats['totalTransfers'])" description="Between locations" tone="info">
            <x-slot:icon>@svg('heroicon-o-arrows-right-left', 'h-5 w-5 text-sky-500')</x-slot:icon>
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
                        <th class="px-5 py-3">DATE</th>
                        <th class="px-5 py-3">TYPE</th>
                        <th class="px-5 py-3">PRODUCT</th>
                        <th class="px-5 py-3">QUANTITY</th>
                        <th class="px-5 py-3">REFERENCE</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                    @foreach ($data as $row)
                        @php $typeColors = ['in' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400', 'out' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400', 'transfer' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400']; @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">{{ $row['date'] }}</td>
                            <td class="whitespace-nowrap px-5 py-4"><span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $typeColors[$row['type']] }}">{{ ucfirst($row['type']) }}</span></td>
                            <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $row['product'] }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">{{ number_format($row['quantity']) }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">{{ $row['reference'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
