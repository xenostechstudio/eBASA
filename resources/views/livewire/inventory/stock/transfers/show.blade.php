<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Inventory · Stock</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text:white">Transfer {{ $transfer['reference'] ?? '' }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text:white/60">Review the details of this stock transfer.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('inventory.stock.transfers') }}"
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border:white/20 dark:bg:white/10 dark:text:white dark:hover:bg:white/20">
                Back to transfers
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Details --}}
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border:white/10 dark:bg:white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text:white">Transfer Details</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text:white/50">Core information captured when this transfer was created.</p>

                <dl class="mt-6 grid gap-4 text-sm md:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text:white/50">Reference</dt>
                        <dd class="mt-1 font-medium text-slate-900 dark:text:white">{{ $transfer['reference'] ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text:white/50">Status</dt>
                        <dd class="mt-1">
                            @php $status = $transfer['status'] ?? 'pending'; @endphp
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                                    'in_transit' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400',
                                    'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                ];
                            @endphp
                            <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $statusColors[$status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ str($status)->headline() }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text:white/50">From warehouse</dt>
                        <dd class="mt-1 text-slate-700 dark:text:white/70">{{ $transfer['from_warehouse'] ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text:white/50">To warehouse</dt>
                        <dd class="mt-1 text-slate-700 dark:text:white/70">{{ $transfer['to_warehouse'] ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text:white/50">Items</dt>
                        <dd class="mt-1 text-slate-900 dark:text:white font-medium">{{ $transfer['items_count'] ?? 0 }} items</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text:white/50">Date</dt>
                        <dd class="mt-1 text-slate-700 dark:text:white/70">{{ optional($transfer['created_at'] ?? null)->format('d M Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Summary card --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24 rounded-2xl border border-slate-200 bg:white p-6 shadow-sm dark:border:white/10 dark:bg:white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text:white">Transfer Summary</h3>

                <dl class="mt-6 space-y-4 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-500 dark:text:white/60">Direction</dt>
                        <dd class="font-medium text-slate-900 dark:text:white">
                            {{ $transfer['from_warehouse'] ?? '—' }} → {{ $transfer['to_warehouse'] ?? '—' }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500 dark:text:white/60">Items moved</dt>
                        <dd class="font-medium text-slate-900 dark:text:white">{{ $transfer['items_count'] ?? 0 }}</dd>
                    </div>
                    <div class="border-t border-slate-200 pt-4 text-xs text-slate-500 dark:border:white/10 dark:text:white/60">
                        This view is read-only. To correct a transfer, create a new compensating transfer.
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
