<div class="flex flex-1 min-h-0 flex-col gap-6 text-slate-900 dark:text-white">
    <header class="flex flex-col gap-3 rounded-3xl border border-slate-200/70 bg-white/90 p-4 shadow-sm backdrop-blur dark:border-white/10 dark:bg-white/5">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-500 dark:text-white/60">Active Shift</p>
                <h1 class="mt-1 text-2xl font-semibold text-slate-900 dark:text-white">{{ $shiftSummary['branch'] }}</h1>
                <p class="text-slate-600 dark:text-white/80">Cashier: <span class="font-medium text-slate-900 dark:text-white">{{ $shiftSummary['cashier'] }}</span> · Started {{ $shiftSummary['since'] }}</p>
            </div>
            <div class="flex flex-wrap gap-2 text-xs lg:items-center lg:justify-end">
                <button type="button"
                    x-data="{ isDark: document.documentElement.classList.contains('dark'), toggleTheme() { const dark = document.documentElement.classList.toggle('dark'); this.isDark = dark; localStorage.setItem('theme', dark ? 'dark' : 'light'); } }"
                    @click="toggleTheme()"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-3 py-2 text-slate-700 hover:bg-slate-100 dark:border-white/20 dark:text-white/80 dark:hover:bg-white/10"
                    title="Toggle theme">
                    <svg x-show="!isDark" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="4" />
                        <path d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364-1.414-1.414M7.05 7.05 5.636 5.636m12.728 0-1.414 1.414M7.05 16.95l-1.414 1.414" />
                    </svg>
                    <svg x-show="isDark" x-cloak class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
                    </svg>
                </button>
                <button type="button" x-data class="inline-flex items-center gap-2 rounded-2xl border border-white/20 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-white/80 hover:bg-white/10"
                    @click="$dispatch('open-modal', 'pos-shift-summary')">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 5v14" />
                        <path d="M5 12h14" />
                    </svg>
                    View Summary
                </button>
            </div>
        </div>
        <div class="flex flex-col gap-2 text-sm"></div>
    </header>

    <div class="grid flex-1 gap-6 overflow-hidden lg:grid-cols-12">
        <!-- Session / Sidebar -->
        <section class="flex h-full min-h-0 flex-col gap-5 rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-white/10 dark:bg-slate-950/60 lg:col-span-3">
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-3 rounded-2xl border border-emerald-300/60 bg-emerald-400/10 px-4 py-3">
                    <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 5h2v14H3zM7 5h2v14H7zM11 5h2v14h-2zM15 5h2v14h-2zM19 5h2v14h-2z" />
                    </svg>
                    <input type="text" class="flex-1 bg-transparent text-lg font-semibold text-slate-900 placeholder:text-emerald-900/60 focus:outline-none dark:text-white dark:placeholder:text-emerald-100/60" placeholder="Scan or enter barcode" autofocus>
                    <button type="button" class="rounded-xl border border-emerald-200/50 bg-emerald-300/20 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-900 dark:text-emerald-50">F6</button>
                </div>
                <div class="flex items-center gap-3 rounded-2xl border border-slate-200/70 bg-white/70 px-4 py-3 dark:border-white/10 dark:bg-white/5">
                    <svg class="h-4 w-4 text-slate-500 dark:text-white/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m16.5 16.5 5 5" />
                    </svg>
                    <input type="text" class="flex-1 bg-transparent text-base font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none dark:text-white dark:placeholder:text-white/40" placeholder="Search products or SKU">
                </div>
            </div>

            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-white/60">Quick Actions</p>
                <div class="mt-3 space-y-2">
                    @foreach ($quickActions as $action)
                        <button type="button" class="flex w-full items-center justify-between rounded-2xl border border-slate-200/70 bg-white/70 px-4 py-3 text-left text-sm font-medium text-slate-700 hover:border-slate-400 dark:border-white/10 dark:bg-white/5 dark:text-white/80 dark:hover:border-white/30">
                            <span>{{ $action['label'] }}</span>
                            <span class="text-slate-400 dark:text-white/50">⟶</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mt-auto rounded-2xl border border-amber-400/30 bg-amber-100 px-4 py-3 text-sm text-amber-900 dark:bg-amber-500/10 dark:text-amber-100">
                <p class="text-xs uppercase tracking-[0.3em] text-amber-800 dark:text-white/70">Sync Warning</p>
                <p class="mt-1 font-medium">Inventory update delayed ~2 minutes.</p>
            </div>
        </section>

        <!-- Cart Panel -->
        <section class="flex h-full min-h-0 flex-col gap-4 rounded-3xl border border-slate-200/70 bg-white/90 p-6 shadow-sm dark:border-white/10 dark:bg-slate-950/60 lg:col-span-9">

            <div class="flex flex-1 min-h-0 flex-col -mx-6 -mt-6">
                <div class="grid grid-cols-12 gap-4 border-b border-slate-200/70 px-6 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500 dark:border-white/10 dark:text-white/50">
                    <p class="col-span-3">Barcode</p>
                    <p class="col-span-5">Product</p>
                    <p class="col-span-2 text-center">Qty</p>
                    <p class="col-span-2 text-right">Total</p>
                </div>
                <div class="flex-1 divide-y divide-white/10 overflow-y-auto max-h-[calc(100vh-450px)]">
                    @foreach ($cartItems as $item)
                        <div class="grid grid-cols-12 gap-3 px-6 py-3">
                            <div class="col-span-3 text-sm font-semibold text-slate-500 tabular-nums dark:text-white/70">{{ $item['sku'] }}</div>
                            <div class="col-span-5">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $item['name'] }}</p>
                            </div>
                            <div class="col-span-2 flex items-center justify-center gap-1 text-sm">
                                <button type="button" class="rounded-full bg-slate-200 px-2 text-base leading-none text-slate-700 dark:bg-white/10 dark:text-white">−</button>
                                <span class="text-base font-semibold tabular-nums text-slate-900 dark:text-white">{{ $item['qty'] }}</span>
                                <button type="button" class="rounded-full bg-slate-200 px-2 text-base leading-none text-slate-700 dark:bg-white/10 dark:text-white">+</button>
                            </div>
                            <div class="col-span-2 text-right text-sm font-semibold tabular-nums text-slate-900 dark:text-white">{{ number_format($item['total'], 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-3">
                <button type="button" x-data class="w-full rounded-2xl border border-slate-200/70 bg-gradient-to-br from-blue-500/20 via-indigo-500/20 to-purple-500/20 p-5 text-left transition hover:border-blue-400 dark:border-white/10 dark:bg-gradient-to-br dark:from-blue-500/30 dark:via-indigo-500/30 dark:to-purple-500/30 dark:hover:border-white/40"
                    @click="$dispatch('open-modal', 'pos-checkout-review')">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-700 dark:text-white/80">Grand Total</p>
                            <p class="mt-1 text-4xl font-semibold text-slate-900 tabular-nums dark:text-white">Rp {{ number_format($paymentSummary['total'], 0, ',', '.') }}</p>
                        </div>
                        <svg class="h-6 w-6 text-slate-700 dark:text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m9 6 6 6-6 6" />
                        </svg>
                    </div>
                </button>

                <div class="flex flex-col gap-2">
                    <button type="button" class="rounded-2xl border border-slate-200/70 bg-white py-3 text-center text-base font-semibold text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:bg-white/10 dark:text-white/80 dark:hover:bg-white/20">Park Sale</button>
                </div>
            </div>
        </section>

    </div>

    <x-modal name="pos-shift-summary" maxWidth="5xl">
        <div class="rounded-3xl bg-white/95 p-6 text-slate-900 shadow-2xl shadow-black/20 dark:bg-slate-950/95 dark:text-white dark:shadow-black/50">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/60">Shift Summary</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ $shiftSummary['branch'] }}</h2>
                    <p class="text-slate-600 dark:text-white/70">Cashier <span class="font-semibold text-slate-900 dark:text-white">{{ $shiftSummary['cashier'] }}</span> · Started {{ $shiftSummary['since'] }}</p>
                </div>
                <button type="button" x-data class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-white/10 dark:text-white/70 dark:hover:bg-white/5"
                    @click="$dispatch('close-modal', 'pos-shift-summary')">
                    Close
                </button>
            </div>

            <div class="mt-6 grid gap-3 lg:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-slate-900/60">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-white/60">Sales</p>
                    <p class="mt-1 text-2xl font-semibold tabular-nums text-slate-900 dark:text-white">Rp {{ number_format($shiftSummary['sales'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-slate-900/60">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-white/60">Transactions</p>
                    <p class="mt-1 text-2xl font-semibold tabular-nums text-slate-900 dark:text-white">{{ $shiftSummary['transactions'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-slate-900/60">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-white/60">Cash on Hand</p>
                    <p class="mt-1 text-2xl font-semibold tabular-nums text-slate-900 dark:text-white">Rp {{ number_format($shiftSummary['cashOnHand'], 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-slate-900/40">
                <div class="grid grid-cols-12 gap-3 px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-white/60">
                    <p class="col-span-4">Transaction</p>
                    <p class="col-span-2">Time</p>
                    <p class="col-span-2 text-center">Items</p>
                    <p class="col-span-2">Channel</p>
                    <p class="col-span-2 text-right">Amount</p>
                </div>
                <div class="max-h-80 divide-y divide-slate-200 overflow-y-auto dark:divide-white/5">
                    @foreach ($cashierTransactions as $transaction)
                        <div class="grid grid-cols-12 gap-3 px-5 py-3 text-sm text-slate-700 dark:text-white/80">
                            <p class="col-span-4 font-semibold text-slate-900 dark:text-white">{{ $transaction['number'] }}</p>
                            <p class="col-span-2 text-slate-500 dark:text-white/60">{{ $transaction['time'] }}</p>
                            <p class="col-span-2 text-center tabular-nums">{{ $transaction['items'] }}</p>
                            <p class="col-span-2 text-slate-600 dark:text-white/80">{{ $transaction['channel'] }}</p>
                            <p class="col-span-2 text-right font-semibold tabular-nums text-slate-900 dark:text-white">Rp {{ number_format($transaction['amount'], 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-modal>

    <x-modal name="pos-checkout-review" maxWidth="full">
        <div class="flex h-full w-full flex-col rounded-3xl bg-white/95 p-6 text-slate-900 shadow-2xl shadow-black/20 dark:bg-slate-950/95 dark:text-white dark:shadow-black/50">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-white/60">Checkout Review</p>
                    <h2 class="mt-1 text-2xl font-semibold text-slate-900 dark:text-white">Receipt #{!! rand(2020, 2099) !!}</h2>
                    <p class="text-slate-600 dark:text-white/70">Cashier {{ $shiftSummary['cashier'] }}</p>
                </div>

                <div class="flex flex-col items-end gap-3 lg:flex-row lg:items-center lg:gap-6">
                    <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-700 dark:border-white/15 dark:bg-slate-900/60 dark:text-white/70">
                        <div>
                            <p class="uppercase tracking-[0.25em] text-slate-500 dark:text-white/50">Grand Total</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900 tabular-nums dark:text-white">Rp {{ number_format($paymentSummary['total'], 0, ',', '.') }}</p>
                        </div>
                        <div class="h-10 w-px bg-slate-200 dark:bg-white/10"></div>
                        <div>
                            <p class="uppercase tracking-[0.25em] text-slate-500 dark:text-white/50">Items</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900 tabular-nums dark:text-white">{{ count($cartItems) }}</p>
                        </div>
                        <div>
                            <p class="uppercase tracking-[0.25em] text-slate-500 dark:text-white/50">Discount</p>
                            <p class="mt-1 text-sm font-semibold text-emerald-600 tabular-nums dark:text-emerald-300">-{{ number_format($paymentSummary['discount'], 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex flex-col items-center gap-1">
                            <button type="button"
                                class="inline-flex h-14 w-14 items-center justify-center rounded-full border border-white/30 text-white/80 hover:bg-white/10"
                                @click="$dispatch('close-modal', 'pos-checkout-review')"
                                title="Cancel / Back">
                                {{ svg('heroicon-o-x-mark', 'w-8 h-8') }}
                            </button>
                            <span class="rounded-xl border border-white/30 bg-white/5 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/70">
                                Esc
                            </span>
                        </div>

                        <div class="flex flex-col items-center gap-1">
                            <button type="button"
                                class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-emerald-500 text-white shadow-lg shadow-emerald-500/40 hover:bg-emerald-400"
                                title="Confirm charge">
                                {{ svg('heroicon-o-check-badge', 'w-9 h-9') }}
                            </button>
                            <span class="rounded-xl border border-emerald-200/60 bg-emerald-300/20 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-900 dark:text-emerald-50">
                                Enter
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-1 flex-col gap-5 lg:flex-row">
                <section class="flex-1 space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-slate-900/60">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-600 dark:text-white/60">Promos</h3>
                            <span class="text-xs text-slate-500 dark:text-white/50">{{ count($checkoutPromos) }} applied</span>
                        </div>
                        <div class="mt-3 space-y-2">
                            @foreach ($checkoutPromos as $promo)
                                <div class="flex items-start justify-between rounded-xl bg-white px-3 py-2 dark:bg-white/5">
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $promo['label'] }}</p>
                                        <p class="text-xs text-slate-500 dark:text-white/60">{{ $promo['description'] }}</p>
                                    </div>
                                    <p class="text-sm font-semibold text-emerald-600 tabular-nums dark:text-emerald-300">{{ number_format($promo['amount'], 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-slate-900/60">
                        <label class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-600 dark:text-white/60" for="checkout-payment-method">Payment Method</label>
                        <select id="checkout-payment-method" class="mt-3 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-slate-400 focus:outline-none dark:border-white/10 dark:bg-white/5 dark:text-white/90 dark:focus:border-white/50">
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method['label'] }}">{{ $method['label'] }} @if($method['status'] === 'custom') (Custom) @endif</option>
                            @endforeach
                        </select>
                    </div>

                </section>

                <section class="flex w-full max-w-md flex-none flex-col rounded-3xl border border-slate-200 bg-slate-50 p-6 dark:border-white/10 dark:bg-slate-950/60">
                    <div class="flex h-full flex-col">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-white/60">Summary</p>
                                <p class="text-base font-semibold text-slate-900 dark:text-white/90">Receipt #{!! rand(3020, 3099) !!}</p>
                            </div>
                            <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:bg-white/10 dark:text-white/80 dark:hover:bg-white/20">
                                <svg class="h-4 w-4 text-slate-600 dark:text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2Z" />
                                    <path d="M8 7V5h8v2" />
                                    <path d="M8 13h8" />
                                    <path d="M8 17h4" />
                                </svg>
                                <span>Print</span>
                            </button>
                        </div>
                        <div class="mt-4 flex-1 space-y-2 overflow-y-auto pr-1 text-sm max-h-[calc(100vh-450px)]">
                            @foreach ($cartItems as $item)
                                <div class="flex justify-between text-slate-700 dark:text-white/80">
                                    <span>{{ $item['qty'] }}× {{ Str::limit($item['name'], 30) }}</span>
                                    <span class="tabular-nums">{{ number_format($item['total'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 border-t border-slate-200 pt-4 text-sm dark:border-white/10">
                            <div class="flex justify-between text-slate-700 dark:text-white/70">
                                <span>Subtotal</span>
                                <span class="tabular-nums">{{ number_format($paymentSummary['subtotal'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-emerald-600 dark:text-emerald-200">
                                <span>Discount</span>
                                <span class="tabular-nums">−{{ number_format($paymentSummary['discount'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-700 dark:text-white/70">
                                <span>Tax</span>
                                <span class="tabular-nums">{{ number_format($paymentSummary['tax'], 0, ',', '.') }}</span>
                            </div>
                            <div class="mt-2 flex items-center justify-between text-lg font-semibold text-slate-900 dark:text-white">
                                <span>Total</span>
                                <span class="tabular-nums">Rp {{ number_format($paymentSummary['total'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </x-modal>
</div>
