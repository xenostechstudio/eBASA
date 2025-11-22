<div class="space-y-10">
    <section class="overflow-hidden rounded-[36px] border border-slate-200/70 bg-gradient-to-br from-emerald-50 via-white to-slate-50 p-8 text-slate-900 shadow-[0_45px_120px_-60px_rgba(15,23,42,0.25)] dark:border-white/10 dark:from-emerald-900 dark:via-slate-900/70 dark:to-slate-950/80 dark:text-white">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-center">
            <div class="space-y-5 lg:flex-1">
                <p class="text-xs uppercase tracking-[0.6em] text-emerald-700/80 dark:text-white/50">General Setup</p>
                <h1 class="text-4xl font-semibold text-slate-900 dark:text-white md:text-5xl">Master data & shared configuration cockpit</h1>
                <p class="text-base text-slate-600 dark:text-white/70">Control the foundational records that power POS, Inventory, and HR. Keep branches in sync while guarding data quality.</p>
                <div class="flex flex-wrap gap-3">
                    <button class="rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-slate-900/30 dark:bg-white dark:text-slate-900">Open Filament</button>
                    <button class="rounded-full border border-slate-900/20 px-5 py-2 text-sm font-semibold text-slate-900 hover:border-slate-900/40 dark:border-white/30 dark:text-white dark:hover:border-white/60">Review changes</button>
                    <button class="rounded-full border border-slate-900/10 px-5 py-2 text-sm text-slate-600 hover:border-slate-900/30 dark:border-white/15 dark:text-white/70 dark:hover:border-white/40">Export audit</button>
                </div>
            </div>

            <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white/80 p-6 dark:border-white/10 dark:bg-white/5">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/40">Pulse metrics</p>
                <div class="mt-4 space-y-4">
                    @foreach ($stats as $stat)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 text-slate-900 dark:border-white/10 dark:bg-slate-900/40 dark:text-white">
                            <p class="text-xs uppercase tracking-[0.35em] text-slate-500 dark:text-white/50">{{ $stat['label'] }}</p>
                            <p class="mt-2 text-3xl font-semibold">{{ $stat['value'] }}</p>
                            <p class="text-xs text-emerald-600 dark:text-emerald-300">{{ $stat['trend'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="flex flex-wrap gap-3">
        @foreach ($sections as $key => $label)
            <button type="button"
                wire:click="setSection('{{ $key }}')"
                class="rounded-full px-4 py-2 text-xs uppercase tracking-[0.4em] transition {{ $activeSection === $key ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'border border-slate-200 text-slate-600 hover:border-slate-300 dark:border-white/25 dark:text-white/70 dark:hover:border-white/40' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if ($activeSection === 'master-data')
        <div class="grid gap-6 lg:grid-cols-3">
            @foreach ($configurationAreas as $area)
                <div class="rounded-[28px] border border-slate-200 bg-white/90 p-6 text-left shadow-sm dark:border-white/10 dark:bg-white/5">
                    <p class="text-xs uppercase tracking-[0.35em] text-slate-500 dark:text-white/40">{{ $area['status'] }}</p>
                    <h3 class="mt-3 text-xl font-semibold text-slate-900 dark:text-white">{{ $area['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-white/70">{{ $area['description'] }}</p>
                    <button class="mt-5 rounded-full border border-slate-200 px-4 py-2 text-xs uppercase tracking-[0.3em] text-slate-700 hover:border-slate-400 dark:border-white/30 dark:text-white/80 dark:hover:border-white/60">{{ $area['cta'] }}</button>
                </div>
            @endforeach
        </div>
    @elseif ($activeSection === 'locations')
        <div class="rounded-[28px] border border-slate-200 bg-white/90 p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500 dark:text-white/40">Rollout</p>
                <span class="rounded-full border border-slate-200 px-3 py-1 text-xs text-slate-500 dark:border-white/20 dark:text-white/60">Branches synced weekly</span>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @foreach ($locationRollout as $location)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 text-slate-900 dark:border-white/10 dark:bg-slate-900/40 dark:text-white">
                        <p class="text-sm text-slate-600 dark:text-white/60">{{ $location['type'] }}</p>
                        <p class="mt-2 text-2xl font-semibold">{{ $location['label'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">{{ $location['progress'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif ($activeSection === 'compliance')
        <div class="rounded-[28px] border border-slate-200 bg-white/90 p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500 dark:text-white/40">Checklist</p>
                <span class="rounded-full border border-slate-200 px-3 py-1 text-xs text-slate-500 dark:border-white/20 dark:text-white/60">Escalations sync to Legal</span>
            </div>
            <div class="mt-6 space-y-4">
                @foreach ($complianceChecklist as $item)
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 dark:border-white/10 dark:bg-slate-900/40 dark:text-white">
                        <div>
                            <p class="text-sm font-semibold">{{ $item['item'] }}</p>
                            <p class="text-xs text-slate-600 dark:text-white/60">Owner: {{ $item['owner'] }}</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs uppercase tracking-[0.3em]
                            {{ $item['status'] === 'Green' ? 'bg-emerald-600/15 text-emerald-700 dark:bg-emerald-300/20 dark:text-emerald-200' : ($item['status'] === 'Amber' ? 'bg-amber-500/20 text-amber-700 dark:bg-amber-300/20 dark:text-amber-200' : 'bg-rose-500/20 text-rose-700 dark:bg-rose-300/20 dark:text-rose-200') }}">
                            {{ $item['status'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="rounded-[28px] border border-slate-200 bg-white/90 p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500 dark:text-white/40">Integrations</p>
                <span class="rounded-full border border-slate-200 px-3 py-1 text-xs text-slate-500 dark:border-white/20 dark:text-white/60">Observability wired via Sentry</span>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @foreach ($integrationStatus as $integration)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 text-slate-900 dark:border-white/10 dark:bg-slate-900/40 dark:text-white">
                        <p class="text-sm text-slate-600 dark:text-white/60">{{ $integration['channel'] }}</p>
                        <p class="mt-2 text-xl font-semibold">{{ $integration['name'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/50">{{ $integration['state'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-[28px] border border-slate-200 bg-slate-50/80 p-6 text-left text-slate-900 dark:border-white/10 dark:bg-slate-900/40 dark:text-white">
            <h3 class="text-lg font-semibold">Design principles</h3>
            <ul class="mt-4 space-y-3 text-sm text-slate-600 dark:text-white/70">
                <li>• Use General Setup as the orchestration layer. Heavy CRUD stays in Filament panels.</li>
                <li>• Keep every shared entity tied to a branch and region context, so POS overrides stay scoped.</li>
                <li>• Mirror approvals: Inventory (procurement) vs HR vs Finance. Each track should see the same audit trail.</li>
            </ul>
        </div>
        <div class="rounded-[28px] border border-slate-200 bg-white/90 p-6 text-left text-slate-900 dark:border-white/10 dark:bg-white/5 dark:text-white">
            <h3 class="text-lg font-semibold">Next build candidates</h3>
            <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-white/70">
                <p>1. Branch capability matrix (cold storage, fulfillment, loyalty).</p>
                <p>2. Shared address book for suppliers, logistics partners, and service vendors.</p>
                <p>3. Audit-ready change log streaming to Reports module.</p>
            </div>
        </div>
    </div>
</div>
