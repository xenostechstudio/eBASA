<div class="flex flex-1 min-h-0 flex-col gap-6 text-white">
    <header class="flex flex-col gap-4 rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-white/60">Active Shift</p>
            <h1 class="mt-2 text-2xl font-semibold">{{ $shiftSummary['branch'] }}</h1>
            <p class="text-white/80">Cashier: <span class="font-medium text-white">{{ $shiftSummary['cashier'] }}</span> · Started {{ $shiftSummary['since'] }}</p>
        </div>
        <div class="flex flex-wrap gap-4 text-sm">
            <div class="rounded-2xl border border-white/10 bg-slate-900/50 px-4 py-3">
                <p class="text-white/60">Sales</p>
                <p class="text-xl font-semibold tracking-tight">Rp {{ number_format($shiftSummary['sales'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-slate-900/50 px-4 py-3">
                <p class="text-white/60">Transactions</p>
                <p class="text-xl font-semibold tracking-tight">{{ $shiftSummary['transactions'] }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-slate-900/50 px-4 py-3">
                <p class="text-white/60">Cash on Hand</p>
                <p class="text-xl font-semibold tracking-tight">Rp {{ number_format($shiftSummary['cashOnHand'], 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="flex flex-col gap-2 text-sm">
            @foreach ($notifications as $notification)
                <div @class([
                    'rounded-2xl px-4 py-2 text-sm font-medium shadow-sm border border-white/5',
                    'bg-emerald-500/10 text-emerald-200' => $notification['type'] === 'success',
                    'bg-amber-500/10 text-amber-200' => $notification['type'] === 'warning',
                    'bg-rose-500/10 text-rose-200' => $notification['type'] === 'error',
                ])>
                    {{ $notification['message'] }}
                </div>
            @endforeach
        </div>
    </header>

    <div class="grid flex-1 gap-6 overflow-hidden lg:grid-cols-12">
        <!-- Session / Sidebar -->
        <section class="flex h-full min-h-0 flex-col gap-6 rounded-3xl border border-white/10 bg-slate-950/60 p-6 lg:col-span-3">
            <div class="flex h-full min-h-0 flex-col">
                <h2 class="text-xs uppercase tracking-[0.3em] text-white/60">Session</h2>
                <div class="mt-3 space-y-2 text-sm text-white/80">
                    <div class="flex items-center justify-between">
                        <span>Shift start</span>
                        <span class="font-semibold text-white">{{ $shiftSummary['since'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Status</span>
                        <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-200">Ready</span>
                    </div>
                </div>
            </div>

            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-white/60">Quick Actions</p>
                <div class="mt-3 space-y-2">
                    @foreach ($quickActions as $action)
                        <button type="button" class="flex w-full items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-left text-sm font-medium text-white/80 hover:border-white/30">
                            <span>{{ $action['label'] }}</span>
                            <span class="text-white/50">⟶</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Cart Panel -->
        <section class="flex h-full min-h-0 flex-col gap-4 rounded-3xl border border-white/10 bg-slate-950/60 p-6 lg:col-span-6">
            <div class="flex flex-col gap-3 lg:flex-row">
                <label class="flex flex-1 items-center gap-3 rounded-2xl border border-emerald-400/50 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-50">
                    <span class="text-xs uppercase tracking-[0.3em] text-emerald-200">Scan</span>
                    <input type="text" class="flex-1 bg-transparent text-lg font-semibold outline-none placeholder:text-emerald-100/50" placeholder="Awaiting barcode…" autofocus>
                </label>
                <label class="flex flex-1 items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white/80">
                    <span class="text-xs uppercase tracking-[0.3em] text-white/60">Search</span>
                    <input type="text" class="flex-1 bg-transparent text-base font-medium text-white outline-none placeholder:text-white/40" placeholder="Type product name or SKU">
                </label>
            </div>

            <div class="flex flex-1 min-h-0 flex-col gap-4">
                <div class="flex flex-1 min-h-0 flex-col rounded-2xl border border-white/10 bg-slate-900/40">
                    <div class="grid grid-cols-12 gap-4 border-b border-white/5 px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white/40">
                        <p class="col-span-6">Product</p>
                        <p class="col-span-2 text-center">Qty</p>
                        <p class="col-span-2 text-right">Price</p>
                        <p class="col-span-2 text-right">Total</p>
                    </div>
                    <div class="flex-1 divide-y divide-white/5 overflow-y-auto">
                        @foreach ($cartItems as $item)
                            <div class="grid grid-cols-12 gap-4 px-4 py-4">
                                <div class="col-span-6">
                                    <p class="text-base font-semibold text-white">{{ $item['name'] }}</p>
                                </div>
                                <div class="col-span-2 flex items-center justify-center gap-2">
                                    <button type="button" class="rounded-full bg-white/10 px-2 text-lg leading-none">−</button>
                                    <span class="text-lg font-semibold tabular-nums">{{ $item['qty'] }}</span>
                                    <button type="button" class="rounded-full bg-white/10 px-2 text-lg leading-none">+</button>
                                </div>
                                <div class="col-span-2 text-right text-lg tabular-nums">{{ number_format($item['price'], 0, ',', '.') }}</div>
                                <div class="col-span-2 text-right text-lg font-semibold tabular-nums">{{ number_format($item['total'], 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid gap-3 md:grid-cols-4">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.3em] text-white/60">Subtotal</p>
                    <p class="mt-1 text-2xl font-semibold tabular-nums">Rp {{ number_format($paymentSummary['subtotal'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.3em] text-white/60">Discount</p>
                    <p class="mt-1 text-2xl font-semibold text-emerald-300 tabular-nums">−Rp {{ number_format($paymentSummary['discount'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.3em] text-white/60">Tax</p>
                    <p class="mt-1 text-2xl font-semibold tabular-nums">Rp {{ number_format($paymentSummary['tax'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-blue-500/30 to-indigo-500/30 p-4">
                    <p class="text-xs uppercase tracking-[0.3em] text-white/80">Grand Total</p>
                    <p class="mt-1 text-3xl font-semibold text-white tabular-nums">Rp {{ number_format($paymentSummary['total'], 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex flex-col gap-3 lg:flex-row">
                <button type="button" class="flex-1 rounded-2xl border border-white/10 bg-white/10 py-4 text-center text-lg font-semibold text-white/80 hover:bg-white/20">Park Sale</button>
                <button type="button" class="flex-1 rounded-2xl bg-blue-500 py-4 text-center text-lg font-semibold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-400">Charge Rp {{ number_format($paymentSummary['total'], 0, ',', '.') }}</button>
            </div>
        </section>

        <!-- Utility Panel -->
        <section class="flex h-full min-h-0 flex-col rounded-3xl border border-white/10 bg-slate-950/60 p-6 lg:col-span-3">
            <div class="flex h-full flex-col gap-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-white/60">Summary</p>
                        <p class="text-base font-semibold text-white/90">Receipt #{!! rand(1020, 1099) !!}</p>
                    </div>
                    <div>
                        <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-3 py-2 text-sm font-medium text-white/80 hover:bg-white/20">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2Z" />
                                <path d="M8 7V5h8v2" />
                                <path d="M8 13h8" />
                                <path d="M8 17h4" />
                            </svg>
                            <span>Print</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-2 overflow-y-auto pr-1 text-sm">
                    @foreach ($cartItems as $item)
                        <div class="flex justify-between text-white/70">
                            <span>{{ $item['qty'] }}× {{ Str::limit($item['name'], 18) }}</span>
                            <span class="tabular-nums">{{ number_format($item['total'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-white/10 pt-4 text-sm">
                    <div class="flex justify-between text-white/70">
                        <span>Subtotal</span>
                        <span class="tabular-nums">{{ number_format($paymentSummary['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-emerald-200">
                        <span>Discount</span>
                        <span class="tabular-nums">−{{ number_format($paymentSummary['discount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-white/70">
                        <span>Tax</span>
                        <span class="tabular-nums">{{ number_format($paymentSummary['tax'], 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-lg font-semibold text-white">
                        <span>Total</span>
                        <span class="tabular-nums">Rp {{ number_format($paymentSummary['total'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
