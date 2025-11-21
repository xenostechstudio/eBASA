<div class="space-y-10">
    <section class="overflow-hidden rounded-[36px] border border-white/10 bg-gradient-to-br from-emerald-900 via-slate-900/70 to-slate-950/80 p-8 shadow-[0_45px_120px_-60px_rgba(15,23,42,0.9)]">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-center">
            <div class="space-y-5 lg:flex-1">
                <p class="text-xs uppercase tracking-[0.6em] text-white/50">General Setup</p>
                <h1 class="text-4xl font-semibold text-white md:text-5xl">Master data & shared configuration cockpit</h1>
                <p class="text-base text-white/70">Control the foundational records that power POS, Inventory, and HR. Keep branches in sync while guarding data quality.</p>
                <div class="flex flex-wrap gap-3">
                    <button class="rounded-full bg-white px-5 py-2 text-sm font-semibold text-slate-900 shadow-lg shadow-white/30">Open Filament</button>
                    <button class="rounded-full border border-white/30 px-5 py-2 text-sm font-semibold text-white/80 hover:border-white/60">Review changes</button>
                    <button class="rounded-full border border-white/15 px-5 py-2 text-sm text-white/60 hover:border-white/40">Export audit</button>
                </div>
            </div>

            <div class="w-full max-w-md rounded-3xl border border-white/10 bg-white/5 p-6">
                <p class="text-xs uppercase tracking-[0.4em] text-white/40">Pulse metrics</p>
                <div class="mt-4 space-y-4">
                    @foreach ($stats as $stat)
                        <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                            <p class="text-xs uppercase tracking-[0.35em] text-white/50">{{ $stat['label'] }}</p>
                            <p class="mt-2 text-3xl font-semibold text-white">{{ $stat['value'] }}</p>
                            <p class="text-xs text-emerald-300">{{ $stat['trend'] }}</p>
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
                class="rounded-full px-4 py-2 text-xs uppercase tracking-[0.4em] transition {{ $activeSection === $key ? 'bg-white text-slate-900' : 'border border-white/25 text-white/70 hover:border-white/40' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if ($activeSection === 'master-data')
        <div class="grid gap-6 lg:grid-cols-3">
            @foreach ($configurationAreas as $area)
                <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 text-left">
                    <p class="text-xs uppercase tracking-[0.35em] text-white/40">{{ $area['status'] }}</p>
                    <h3 class="mt-3 text-xl font-semibold text-white">{{ $area['title'] }}</h3>
                    <p class="mt-2 text-sm text-white/70">{{ $area['description'] }}</p>
                    <button class="mt-5 rounded-full border border-white/30 px-4 py-2 text-xs uppercase tracking-[0.3em] text-white/80 hover:border-white/60">{{ $area['cta'] }}</button>
                </div>
            @endforeach
        </div>
    @elseif ($activeSection === 'locations')
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-xs uppercase tracking-[0.35em] text-white/40">Rollout</p>
                <span class="rounded-full border border-white/20 px-3 py-1 text-xs text-white/60">Branches synced weekly</span>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @foreach ($locationRollout as $location)
                    <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                        <p class="text-sm text-white/60">{{ $location['type'] }}</p>
                        <p class="mt-2 text-2xl font-semibold text-white">{{ $location['label'] }}</p>
                        <p class="text-xs text-white/50">{{ $location['progress'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif ($activeSection === 'compliance')
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-xs uppercase tracking-[0.35em] text-white/40">Checklist</p>
                <span class="rounded-full border border-white/20 px-3 py-1 text-xs text-white/60">Escalations sync to Legal</span>
            </div>
            <div class="mt-6 space-y-4">
                @foreach ($complianceChecklist as $item)
                    <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-slate-900/40 px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold text-white">{{ $item['item'] }}</p>
                            <p class="text-xs text-white/60">Owner: {{ $item['owner'] }}</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs uppercase tracking-[0.3em]
                            {{ $item['status'] === 'Green' ? 'bg-emerald-300/20 text-emerald-200' : ($item['status'] === 'Amber' ? 'bg-amber-300/20 text-amber-200' : 'bg-rose-300/20 text-rose-200') }}">
                            {{ $item['status'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6">
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-xs uppercase tracking-[0.35em] text-white/40">Integrations</p>
                <span class="rounded-full border border-white/20 px-3 py-1 text-xs text-white/60">Observability wired via Sentry</span>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @foreach ($integrationStatus as $integration)
                    <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                        <p class="text-sm text-white/60">{{ $integration['channel'] }}</p>
                        <p class="mt-2 text-xl font-semibold text-white">{{ $integration['name'] }}</p>
                        <p class="text-xs text-white/50">{{ $integration['state'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-[28px] border border-white/10 bg-slate-900/40 p-6 text-left">
            <h3 class="text-lg font-semibold text-white">Design principles</h3>
            <ul class="mt-4 space-y-3 text-sm text-white/70">
                <li>• Use General Setup as the orchestration layer. Heavy CRUD stays in Filament panels.</li>
                <li>• Keep every shared entity tied to a branch and region context, so POS overrides stay scoped.</li>
                <li>• Mirror approvals: Inventory (procurement) vs HR vs Finance. Each track should see the same audit trail.</li>
            </ul>
        </div>
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 text-left">
            <h3 class="text-lg font-semibold text-white">Next build candidates</h3>
            <div class="mt-4 space-y-3 text-sm text-white/70">
                <p>1. Branch capability matrix (cold storage, fulfillment, loyalty).</p>
                <p>2. Shared address book for suppliers, logistics partners, and service vendors.</p>
                <p>3. Audit-ready change log streaming to Reports module.</p>
            </div>
        </div>
    </div>
</div>
