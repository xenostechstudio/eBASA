<div class="space-y-10">
    <x-module.heading
        tagline="Procurement"
        title="Suppliers & purchasing cockpit"
        description="Coordinate purchase requests, supplier contracts, and inbound stock for BASA branches."
    >
        <x-slot:actions>
            <x-ui.button variant="secondary">View suppliers</x-ui.button>
            <x-ui.button>Create purchase request</x-ui.button>
        </x-slot:actions>
    </x-module.heading>

    <section class="grid gap-4 md:grid-cols-3">
        @foreach ($summaryCards as $card)
            <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500 dark:text-white/40">{{ $card['label'] }}</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ $card['value'] }}</p>
                @if (!empty($card['hint']))
                    <p class="text-xs text-slate-500 dark:text-white/60">{{ $card['hint'] }}</p>
                @endif
            </div>
        @endforeach
    </section>

    <section class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-slate-500 dark:text-white/40">Activity</p>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Procurement timeline</h3>
                </div>
            </div>
            <div class="mt-4 space-y-4">
                @foreach ($recentActivities as $activity)
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-white/10 dark:bg-slate-950/40">
                        <div>
                            <p class="font-medium text-slate-900 dark:text-white">{{ $activity['title'] }}</p>
                            <p class="text-sm text-slate-500 dark:text-white/50">{{ $activity['subtitle'] }}</p>
                        </div>
                        <span class="text-xs text-slate-500 dark:text-white/50">{{ $activity['time'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <p class="text-xs uppercase tracking-[0.35em] text-slate-500 dark:text-white/40">Playbook</p>
            <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Standard purchasing flow</h3>
            <ol class="mt-4 space-y-2 text-sm text-slate-600 dark:text-white/70 list-decimal list-inside">
                <li>Branch raises purchase request for a supplier or product group.</li>
                <li>Procurement reviews quantities, targets, and budget alignment.</li>
                <li>Approved requests convert into POs and are shared with suppliers.</li>
                <li>Inbound deliveries sync back to Inventory for stock updates.</li>
            </ol>
        </div>
    </section>
</div>
