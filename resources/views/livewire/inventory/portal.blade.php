<div class="space-y-10">
    <x-module.heading
        tagline="Inventory"
        title="Operational control center"
        description="Monitor stock health, branch readiness, and upcoming workstreams across BASA retail."
    >
        <x-slot:actions>
            <x-ui.button variant="secondary">Download report</x-ui.button>
            <x-ui.button as="a" href="{{ route('inventory.branches.index') }}">Branch directory</x-ui.button>
        </x-slot:actions>
    </x-module.heading>

    <section class="grid gap-4 md:grid-cols-3">
        @foreach ($summaryCards as $card)
            <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500 dark:text-white/40">{{ $card['label'] }}</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ $card['value'] }}</p>
                <p class="text-xs text-slate-500 dark:text-white/60">{{ $card['trend'] }}</p>
            </div>
        @endforeach
    </section>

    <section class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-slate-500 dark:text-white/40">Activity</p>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Live operations feed</h3>
                </div>
                <div class="flex gap-2 text-xs text-slate-500 dark:text-white/60">
                    <button class="rounded-full border border-slate-300 bg-white px-3 py-1 text-slate-700 hover:bg-slate-50 dark:border-white/20 dark:bg-white/5 dark:text-white/80 dark:hover:bg-white/10">Inbound</button>
                    <button class="rounded-full border border-slate-200 bg-white/80 px-3 py-1 text-slate-400 hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-white/40 dark:hover:bg-white/10">Outbound</button>
                </div>
            </div>
            <div class="mt-4 space-y-4">
                @foreach ($recentActivities as $activity)
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-white/10 dark:bg-slate-950/40">
                        <div>
                            <p class="font-medium text-slate-900 dark:text-white">{{ $activity['title'] }}</p>
                            <p class="text-sm text-slate-500 dark:text-white/50">{{ $activity['timestamp'] }}</p>
                        </div>
                        <span class="rounded-full border border-slate-200 px-3 py-1 text-xs uppercase tracking-wide text-slate-700 dark:border-white/20 dark:text-white/70">
                            {{ $activity['type'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <p class="text-xs uppercase tracking-[0.35em] text-slate-500 dark:text-white/40">Branch readiness</p>
            <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Health monitor</h3>
            <div class="mt-4 space-y-4">
                @foreach ($branchHealth as $branch)
                    <div>
                        <div class="flex items-center justify-between text-sm text-slate-700 dark:text-white/70">
                            <span>{{ $branch['name'] }}</span>
                            <span class="text-xs uppercase text-slate-400 dark:text-white/40">{{ $branch['status'] }}</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-slate-200 dark:bg-white/10">
                            <div class="h-2 rounded-full bg-slate-900 dark:bg-white/80" style="width: {{ $branch['fill'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700 dark:border-white/10 dark:bg-slate-900/40 dark:text-white/70">
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500 dark:text-white/40">Upcoming</p>
                <p class="mt-1">Stock audit week starts Monday. Ensure transfer notes are synced before 09:00.</p>
            </div>
        </div>
    </section>
</div>
