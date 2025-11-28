<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-3">
        <x-stat.card label="Products Sold" :value="number_format($stats['totalProducts'])" description="Unique items" tone="neutral">
            <x-slot:icon>@svg('heroicon-o-shopping-bag', 'h-5 w-5 text-slate-500')</x-slot:icon>
        </x-stat.card>
        <x-stat.card label="Total Revenue" :value="'Rp ' . number_format($stats['totalRevenue'] / 1000000, 1) . 'M'" description="From products" tone="success">
            <x-slot:icon>@svg('heroicon-o-banknotes', 'h-5 w-5 text-emerald-500')</x-slot:icon>
        </x-stat.card>
        <x-stat.card label="Units Sold" :value="number_format($stats['totalQuantity'])" description="Total quantity" tone="info">
            <x-slot:icon>@svg('heroicon-o-cube', 'h-5 w-5 text-sky-500')</x-slot:icon>
        </x-stat.card>
    </div>

    <div class="rounded-2xl border border-slate-300 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex items-center justify-end gap-3">
                <div class="relative">
                    @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products..."
                        class="h-10 w-64 rounded-xl border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40">
                </div>
                <x-table.export-dropdown aria-label="Export report" />
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/60">
                        <th class="px-5 py-3">PRODUCT</th>
                        <th class="px-5 py-3">SKU</th>
                        <th class="px-5 py-3">CATEGORY</th>
                        <th class="px-5 py-3">QUANTITY</th>
                        <th class="px-5 py-3">REVENUE</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                    @foreach ($data as $row)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                            <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-900 dark:text-white">{{ $row['name'] }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">{{ $row['sku'] }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">{{ $row['category'] }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">{{ number_format($row['quantity']) }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-slate-900 dark:text-white">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
