<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-4">
        <x-stat.card label="Total Sales" :value="'Rp ' . number_format($stats['totalSales'] / 1000000, 1) . 'M'" description="Selected period" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-banknotes', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Total Orders" :value="number_format($stats['totalOrders'])" description="Transactions" tone="info">
            <x-slot:icon>
                @svg('heroicon-o-shopping-cart', 'h-5 w-5 text-sky-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Daily Average" :value="'Rp ' . number_format($stats['avgDaily'] / 1000000, 1) . 'M'" description="Per day" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-calculator', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Best Day" :value="$stats['bestDay']['date']" :description="'Rp ' . number_format($stats['bestDay']['sales'] / 1000000, 1) . 'M'" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-trophy', 'h-5 w-5 text-amber-500')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="flex flex-wrap items-center gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-500 dark:text-white/50">From</label>
                <input type="date" wire:model.live="dateFrom"
                    class="mt-1 h-10 rounded-lg border border-slate-300 bg-white px-3 text-sm dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 dark:text-white/50">To</label>
                <input type="date" wire:model.live="dateTo"
                    class="mt-1 h-10 rounded-lg border border-slate-300 bg-white px-3 text-sm dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
            </div>
            <div class="ml-auto flex items-center gap-2">
                <x-table.export-dropdown aria-label="Export report" />
            </div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/60">
                        <th class="px-5 py-3">DATE</th>
                        <th class="px-5 py-3">SALES</th>
                        <th class="px-5 py-3">ORDERS</th>
                        <th class="px-5 py-3">AVG ORDER</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                    @foreach ($data as $row)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                            <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-900 dark:text-white">
                                {{ $row['date'] }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                Rp {{ number_format($row['sales'], 0, ',', '.') }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                {{ $row['orders'] }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                Rp {{ number_format($row['sales'] / max($row['orders'], 1), 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-slate-200 bg-slate-50 font-medium dark:border-white/10 dark:bg-white/5">
                        <td class="px-5 py-3 text-slate-900 dark:text-white">Total</td>
                        <td class="px-5 py-3 text-slate-900 dark:text-white">Rp {{ number_format($stats['totalSales'], 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-slate-900 dark:text-white">{{ $stats['totalOrders'] }}</td>
                        <td class="px-5 py-3 text-slate-900 dark:text-white">Rp {{ number_format($stats['totalSales'] / max($stats['totalOrders'], 1), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
