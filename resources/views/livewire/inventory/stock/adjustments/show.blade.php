<div class="space-y-6">
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'" :title="$flash['title'] ?? null">
            {{ $flash['message'] ?? '' }}
        </x-alert>
    @endif

    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Inventory · Stock</p>
            <div class="mt-1 flex items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Adjustment {{ $adjustment['reference'] ?? '' }}</h1>
                @php
                    $status = $adjustment['status'] ?? 'draft';
                    $statusLabel = match ($status) {
                        'draft' => 'Draft',
                        'on_process' => 'On process',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        default => ucfirst($status),
                    };
                    $statusClasses = [
                        'draft' => 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-white/70',
                        'on_process' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-300',
                        'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300',
                        'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300',
                    ][$status] ?? 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-white/70';
                @endphp
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $statusClasses }}">
                    {{ $statusLabel }}
                </span>
            </div>
            <p class="mt-1 text-sm text-slate-500 dark:text-white/60">Review the details of this stock adjustment.</p>
        </div>
        <div class="flex items-center gap-3">
            @php $state = $adjustment['status'] ?? 'draft'; @endphp

            @if ($state === 'draft')
                <button wire:click="markOnProcess"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-sky-600 px-4 text-sm font-medium text-white transition hover:bg-sky-700">
                    @svg('heroicon-o-arrow-path', 'h-4 w-4')
                    <span>Mark as On Process</span>
                </button>
            @elseif ($state === 'on_process')
                <button wire:click="markCompleted"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-emerald-600 px-4 text-sm font-medium text-white transition hover:bg-emerald-700">
                    @svg('heroicon-o-check-circle', 'h-4 w-4')
                    <span>Mark as Completed</span>
                </button>
                <button wire:click="markCancelled"
                    class="inline-flex h-10 items-center gap-2 rounded-xl border border-rose-200 bg-white px-4 text-sm font-medium text-rose-700 transition hover:bg-rose-50 dark:border-rose-500/40 dark:bg-transparent dark:text-rose-300 dark:hover:bg-rose-500/10">
                    @svg('heroicon-o-x-circle', 'h-4 w-4')
                    <span>Cancel</span>
                </button>
            @endif
            <a href="{{ route('inventory.stock.adjustments') }}"
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                Back to adjustments
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Details --}}
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text:white">Adjustment Details</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text:white/50">Core information captured when this adjustment was created.</p>

                <dl class="mt-6 grid gap-4 text-sm md:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text:white/50">Reference</dt>
                        <dd class="mt-1 font-medium text-slate-900 dark:text:white">{{ $adjustment['reference'] ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text:white/50">Warehouse</dt>
                        <dd class="mt-1 text-slate-700 dark:text:white/70">{{ $adjustment['warehouse'] ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text:white/50">Product</dt>
                        <dd class="mt-1 text-slate-700 dark:text:white/70">{{ $adjustment['product_name'] ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text:white/50">Type</dt>
                        <dd class="mt-1">
                            @php $type = $adjustment['type'] ?? 'addition'; @endphp
                            <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $type === 'addition' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400' }}">
                                {{ ucfirst($type) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text:white/50">Quantity</dt>
                        <dd class="mt-1 text-slate-900 dark:text:white font-medium">
                            @if (($adjustment['type'] ?? 'addition') === 'addition')
                                +
                            @else
                                -
                            @endif
                            {{ number_format($adjustment['quantity'] ?? 0) }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text:white/50">Date</dt>
                        <dd class="mt-1 text-slate-700 dark:text:white/70">{{ optional($adjustment['created_at'] ?? null)->format('d M Y H:i') }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text:white/50">Reason</dt>
                        <dd class="mt-1 text-slate-700 dark:text:white/70">{{ $adjustment['reason'] ?? '—' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Summary card --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text:white">Adjustment Summary</h3>
                <dl class="mt-6 space-y-4 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-500 dark:text:white/60">Net movement</dt>
                        <dd class="font-medium text-slate-900 dark:text:white">
                            @if (($adjustment['type'] ?? 'addition') === 'addition')
                                +
                            @else
                                -
                            @endif
                            {{ number_format($adjustment['quantity'] ?? 0) }}
                        </dd>
                    </div>
                    <div class="border-t border-slate-200 pt-4 text-xs text-slate-500 dark:border-white/10 dark:text:white/60">
                        This view is read-only. Any corrections should be made with a new adjustment for auditability.
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
