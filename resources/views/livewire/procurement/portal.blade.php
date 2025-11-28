<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-4">
        <x-stat.card label="Suppliers" :value="number_format($stats['suppliers'])" description="Active vendors" tone="neutral">
            <x-slot:icon>
                @svg('heroicon-o-building-office', 'h-5 w-5 text-slate-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Pending Orders" :value="number_format($stats['pendingOrders'])" description="Awaiting approval" tone="warning">
            <x-slot:icon>
                @svg('heroicon-o-clock', 'h-5 w-5 text-amber-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="This Month" :value="number_format($stats['thisMonth'])" description="Orders placed" tone="info">
            <x-slot:icon>
                @svg('heroicon-o-calendar', 'h-5 w-5 text-sky-500')
            </x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Total Value" :value="'Rp ' . number_format($stats['totalValue'] / 1000000, 0) . 'M'" description="This month" tone="success">
            <x-slot:icon>
                @svg('heroicon-o-banknotes', 'h-5 w-5 text-emerald-500')
            </x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Quick Links --}}
    <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-5">
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
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Procurement Timeline</h3>
                </div>
                <a href="{{ route('procurement.orders') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-white/60 dark:hover:text-white">
                    View all
                </a>
            </div>
            <div class="mt-4 space-y-3">
                @foreach ($recentActivities as $activity)
                    @php
                        $typeColors = [
                            'approval' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                            'supplier' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400',
                            'receipt' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                        ];
                    @endphp
                    <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-white/10 dark:bg-slate-950/40">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg {{ $typeColors[$activity['type']] ?? 'bg-slate-100 text-slate-600' }}">
                                @if ($activity['type'] === 'approval')
                                    @svg('heroicon-o-check', 'h-4 w-4')
                                @elseif ($activity['type'] === 'supplier')
                                    @svg('heroicon-o-building-office', 'h-4 w-4')
                                @else
                                    @svg('heroicon-o-inbox-arrow-down', 'h-4 w-4')
                                @endif
                            </span>
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white">{{ $activity['title'] }}</p>
                                <p class="text-sm text-slate-500 dark:text-white/50">{{ $activity['subtitle'] }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-slate-500 dark:text-white/50">{{ $activity['time'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Purchasing Flow --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/40">Playbook</p>
            <h3 class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">Purchasing Flow</h3>
            <ol class="mt-4 space-y-3 text-sm">
                <li class="flex items-start gap-3">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-medium text-white dark:bg-white dark:text-slate-900">1</span>
                    <span class="text-slate-600 dark:text-white/70">Branch raises purchase request for products</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-medium text-white dark:bg-white dark:text-slate-900">2</span>
                    <span class="text-slate-600 dark:text-white/70">Procurement reviews and approves request</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-medium text-white dark:bg-white dark:text-slate-900">3</span>
                    <span class="text-slate-600 dark:text-white/70">PO created and sent to supplier</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-medium text-white dark:bg-white dark:text-slate-900">4</span>
                    <span class="text-slate-600 dark:text-white/70">Goods received and stock updated</span>
                </li>
            </ol>
        </div>
    </div>
</div>
