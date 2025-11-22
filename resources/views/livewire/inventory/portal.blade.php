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
            <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                <p class="text-xs uppercase tracking-[0.35em] text-white/40">{{ $card['label'] }}</p>
                <p class="mt-2 text-3xl font-semibold text-white">{{ $card['value'] }}</p>
                <p class="text-xs text-white/60">{{ $card['trend'] }}</p>
            </div>
        @endforeach
    </section>

    <section class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-white/40">Activity</p>
                    <h3 class="text-lg font-semibold text-white">Live operations feed</h3>
                </div>
                <div class="flex gap-2 text-xs text-white/60">
                    <button class="rounded-full border border-white/20 px-3 py-1">Inbound</button>
                    <button class="rounded-full border border-white/10/50 px-3 py-1 text-white/40">Outbound</button>
                </div>
            </div>
            <div class="mt-4 space-y-4">
                @foreach ($recentActivities as $activity)
                    <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3">
                        <div>
                            <p class="font-medium text-white">{{ $activity['title'] }}</p>
                            <p class="text-sm text-white/50">{{ $activity['timestamp'] }}</p>
                        </div>
                        <span class="rounded-full border border-white/20 px-3 py-1 text-xs uppercase tracking-wide text-white/70">
                            {{ $activity['type'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6">
            <p class="text-xs uppercase tracking-[0.35em] text-white/40">Branch readiness</p>
            <h3 class="mt-2 text-lg font-semibold text-white">Health monitor</h3>
            <div class="mt-4 space-y-4">
                @foreach ($branchHealth as $branch)
                    <div>
                        <div class="flex items-center justify-between text-sm text-white/70">
                            <span>{{ $branch['name'] }}</span>
                            <span class="text-xs uppercase text-white/40">{{ $branch['status'] }}</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-white/10">
                            <div class="h-2 rounded-full bg-white/80" style="width: {{ $branch['fill'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 rounded-2xl border border-white/10 bg-slate-900/40 p-4 text-sm text-white/70">
                <p class="text-xs uppercase tracking-[0.35em] text-white/40">Upcoming</p>
                <p class="mt-1">Stock audit week starts Monday. Ensure transfer notes are synced before 09:00.</p>
            </div>
        </div>
    </section>
</div>
