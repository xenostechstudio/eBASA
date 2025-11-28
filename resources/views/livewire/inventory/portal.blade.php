<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-4">
        <x-stat.card label="Total Products" :value="number_format($stats['totalProducts'])" description="In catalog" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-cube', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Warehouses" :value="number_format($stats['warehouses'])" description="Storage locations" tone="info">
            <x-slot:icon>
                @svg('heroicon-o-building-storefront', 'h-5 w-5 text-sky-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="In Stock" :value="number_format($stats['inStock'])" description="Available items" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-check-circle', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Low Stock" :value="number_format($stats['lowStock'])" description="Need restocking" tone="warning">
            <x-slot:icon>
                @svg('heroicon-o-exclamation-triangle', 'h-5 w-5 text-amber-500')
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
        {{-- Recent Activities --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/40">Activity</p>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Live Operations Feed</h3>
                </div>
                <div class="flex gap-2 text-xs">
                    <button class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-slate-700 hover:bg-slate-50 dark:border-white/20 dark:bg-white/5 dark:text-white/80 dark:hover:bg-white/10">Inbound</button>
                    <button class="rounded-lg border border-slate-200 bg-white/80 px-3 py-1.5 text-slate-400 hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-white/40 dark:hover:bg-white/10">Outbound</button>
                </div>
            </div>
            <div class="mt-4 space-y-3">
                @foreach ($recentActivities as $activity)
                    <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-white/10 dark:bg-slate-950/40">
                        <div>
                            <p class="font-medium text-slate-900 dark:text-white">{{ $activity['title'] }}</p>
                            <p class="text-sm text-slate-500 dark:text-white/50">{{ $activity['timestamp'] }}</p>
                        </div>
                        <span class="rounded-lg border border-slate-200 px-2.5 py-1 text-xs font-medium uppercase tracking-wide text-slate-600 dark:border-white/20 dark:text-white/70">
                            {{ $activity['type'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Warehouse Health --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/40">Warehouse Health</p>
            <h3 class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">Capacity Monitor</h3>
            <div class="mt-4 space-y-4">
                @foreach ($warehouseHealth as $warehouse)
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium text-slate-700 dark:text-white/80">{{ $warehouse['name'] }}</span>
                            <span class="text-xs text-slate-500 dark:text-white/50">{{ $warehouse['branch'] }}</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-slate-200 dark:bg-white/10">
                            <div class="h-2 rounded-full {{ $warehouse['fill'] >= 90 ? 'bg-emerald-500' : ($warehouse['fill'] >= 75 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $warehouse['fill'] }}%"></div>
                        </div>
                        <p class="mt-1 text-right text-xs text-slate-400 dark:text-white/40">{{ $warehouse['fill'] }}% capacity</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm dark:border-white/10 dark:bg-slate-900/40">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/40">Upcoming</p>
                <p class="mt-1 text-slate-700 dark:text-white/70">Stock audit week starts Monday. Ensure transfer notes are synced before 09:00.</p>
            </div>
        </div>
    </div>
</div>
