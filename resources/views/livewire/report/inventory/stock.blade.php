<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-4">
        <x-stat.card label="Products" :value="number_format($stats['totalProducts'])" description="In catalog" tone="neutral">
            <x-slot:icon>@svg('heroicon-o-cube', 'h-5 w-5 text-slate-500')</x-slot:icon>
        </x-stat.card>
        <x-stat.card label="Stock Value" :value="'Rp ' . number_format($stats['totalValue'] / 1000000, 1) . 'M'" description="Total valuation" tone="success">
            <x-slot:icon>@svg('heroicon-o-banknotes', 'h-5 w-5 text-emerald-500')</x-slot:icon>
        </x-stat.card>
        <x-stat.card label="Low Stock" :value="number_format($stats['lowStock'])" description="Need reorder" tone="warning">
            <x-slot:icon>@svg('heroicon-o-exclamation-triangle', 'h-5 w-5 text-amber-500')</x-slot:icon>
        </x-stat.card>
        <x-stat.card label="Out of Stock" :value="number_format($stats['outOfStock'])" description="Unavailable" tone="danger">
            <x-slot:icon>@svg('heroicon-o-x-circle', 'h-5 w-5 text-rose-500')</x-slot:icon>
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
                        <th class="px-5 py-3">PRODUCT</th>
                        <th class="px-5 py-3">SKU</th>
                        <th class="px-5 py-3">QUANTITY</th>
                        <th class="px-5 py-3">VALUE</th>
                        <th class="px-5 py-3">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                    @foreach ($data as $row)
                        @php $statusColors = ['in_stock' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400', 'low_stock' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400', 'out_of_stock' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400']; @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                            <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $row['name'] }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">{{ $row['sku'] }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">{{ number_format($row['quantity']) }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">Rp {{ number_format($row['value'], 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-5 py-4"><span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $statusColors[$row['status']] }}">{{ str($row['status'])->headline() }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
