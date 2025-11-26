<div class="flex flex-1 min-h-0 flex-col gap-6 text-slate-900 dark:text-white"
    x-data="{ checkoutOpen: false }"
    @open-modal.window="if ($event.detail === 'pos-checkout-review') checkoutOpen = true"
    @close-modal.window="if ($event.detail === 'pos-checkout-review') checkoutOpen = false"
    @modal-opened.window="if ($event.detail === 'pos-checkout-review') checkoutOpen = true"
    @modal-closed.window="if ($event.detail === 'pos-checkout-review') checkoutOpen = false"
    @keydown.window.enter.prevent="if (!checkoutOpen) { checkoutOpen = true; $dispatch('open-modal', 'pos-checkout-review'); }">
    <header class="relative flex flex-col gap-3 rounded-3xl border border-slate-200/70 bg-white/90 p-4 shadow-sm backdrop-blur dark:border-white/10 dark:bg-white/5">
        <button type="button"
            x-data="{ isDark: document.documentElement.classList.contains('dark'), toggleTheme() { const dark = document.documentElement.classList.toggle('dark'); this.isDark = dark; localStorage.setItem('theme', dark ? 'dark' : 'light'); } }"
            @click="toggleTheme()"
            class="absolute right-4 top-4 inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-slate-700 shadow-sm hover:bg-slate-100 dark:border-white/20 dark:bg-white/10 dark:text-white/80 dark:hover:bg-white/20"
            title="Toggle theme">
            <svg x-show="!isDark" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="4" />
                <path d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364-1.414-1.414M7.05 7.05 5.636 5.636m12.728 0-1.414 1.414M7.05 16.95l-1.414 1.414" />
            </svg>
            <svg x-show="isDark" x-cloak class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
            </svg>
        </button>

        <div class="flex flex-col gap-3 pr-12 lg:flex-row lg:items-center lg:justify-between lg:pr-0">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-500 dark:text-white/60">Active Shift</p>
                <h1 class="mt-1 text-2xl font-semibold text-slate-900 dark:text-white">{{ $shiftSummary['branch'] }}</h1>
                <p class="text-slate-600 dark:text-white/80">Cashier: <span class="font-medium text-slate-900 dark:text-white">{{ $shiftSummary['cashier'] }}</span> · Started {{ $shiftSummary['since'] }}</p>
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

            <div class="mt-auto">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-white/60">Quick Actions</p>
                <div class="mt-3 space-y-2">
                    <div class="flex gap-2">
                        <button type="button" class="flex flex-1 items-center justify-between rounded-2xl border border-slate-200/70 bg-white/70 px-4 py-3 text-left text-sm font-medium text-slate-700 hover:border-slate-400 dark:border-white/10 dark:bg-white/5 dark:text-white/80 dark:hover:border-white/30">
                            <span class="inline-flex items-center gap-2">
                                {{ svg('heroicon-o-pause-circle', 'w-4 h-4 text-amber-500 dark:text-amber-300') }}
                                <span>Suspend Sale</span>
                            </span>
                            <span class="text-slate-400 dark:text-white/50">⟶</span>
                        </button>

                        <button type="button" class="flex flex-1 items-center justify-between rounded-2xl border border-slate-200/70 bg-white/70 px-4 py-3 text-left text-sm font-medium text-slate-700 hover:border-slate-400 dark:border-white/10 dark:bg-white/5 dark:text-white/80 dark:hover:border-white/30">
                            <span class="inline-flex items-center gap-2">
                                {{ svg('heroicon-o-archive-box', 'w-4 h-4 text-sky-500 dark:text-sky-300') }}
                                <span>Park Sale</span>
                            </span>
                            <span class="text-slate-400 dark:text-white/50">⟶</span>
                        </button>
                    </div>

                    <button type="button" x-data
                        class="flex w-full items-center justify-between rounded-2xl border border-slate-200/70 bg-white/70 px-4 py-3 text-left text-sm font-medium text-slate-700 hover:border-slate-400 dark:border-white/10 dark:bg-white/5 dark:text-white/80 dark:hover:border-white/30"
                        @click="$dispatch('open-modal', 'pos-shift-summary')">
                        <span class="inline-flex items-center gap-2">
                            {{ svg('heroicon-o-document-currency-dollar', 'w-4 h-4 text-emerald-500 dark:text-emerald-300') }}
                            <span>View Shift Summary</span>
                        </span>
                        <span class="text-slate-400 dark:text-white/50">⟶</span>
                    </button>
                </div>
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
                <div class="flex-1 divide-y divide-white/10 overflow-y-auto max-h-[calc(100vh-380px)]">
                    @foreach ($cartItems as $item)
                        <div class="grid grid-cols-12 gap-3 px-6 py-3">
                            <div class="col-span-3 text-sm font-semibold text-slate-500 tabular-nums dark:text-white/70">{{ $item['sku'] }}</div>
                            <div class="col-span-5">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $item['name'] }}</p>
                                <p class="text-xs text-slate-500 dark:text-white/60">@ Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                            <div class="col-span-2 flex items-center justify-center gap-1 text-sm">
                                <button type="button" class="rounded-full bg-slate-200 px-2 text-base leading-none text-slate-700 dark:bg-white/10 dark:text-white">−</button>
                                <span class="text-base font-semibold tabular-nums text-slate-900 dark:text-white">{{ $item['qty'] }}</span>
                                <button type="button" class="rounded-full bg-slate-200 px-2 text-base leading-none text-slate-700 dark:bg-white/10 dark:text-white">+</button>
                            </div>
                            <div class="group col-span-2 text-right text-sm font-semibold tabular-nums text-slate-900 dark:text-white">
                                {{ number_format($item['total'], 0, ',', '.') }}
                                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-rose-500 opacity-0 transition group-hover:opacity-100 dark:text-rose-300">Remove</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-stretch gap-3">
                    <div class="flex w-28 flex-col items-center justify-center rounded-2xl border border-emerald-300/60 bg-white/80 p-3 text-emerald-800 shadow-sm dark:border-emerald-400/40 dark:bg-emerald-500/10 dark:text-emerald-100/80">
                        <p class="text-[10px] uppercase tracking-[0.3em]">Items</p>
                        <p class="mt-1 text-3xl font-semibold tabular-nums">{{ $cartItems ? array_sum(array_column($cartItems, 'qty')) : 0 }}</p>
                    </div>

                    <button type="button" x-data class="relative flex-1 rounded-2xl border border-emerald-300/60 bg-emerald-400/15 px-5 py-4 text-left shadow-inner shadow-emerald-500/10 transition hover:border-emerald-400 dark:border-emerald-400/40 dark:bg-emerald-500/20 dark:hover:border-emerald-300"
                        @click="$dispatch('open-modal', 'pos-checkout-review'); checkoutOpen = true">
                        <span class="pointer-events-none absolute -top-2 right-5 rounded-full border border-emerald-200 bg-white px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.3em] text-emerald-800 shadow-sm dark:border-emerald-400/60 dark:bg-emerald-500/20 dark:text-emerald-50">Enter</span>
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-emerald-800 dark:text-emerald-100/80">Grand Total</p>
                                <p class="mt-1 text-4xl font-semibold text-emerald-900 tabular-nums dark:text-emerald-50">Rp {{ number_format($paymentSummary['total'], 0, ',', '.') }}</p>
                            </div>
                            <svg class="h-6 w-6 text-emerald-800 dark:text-emerald-100/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m9 6 6 6-6 6" />
                            </svg>
                        </div>
                    </button>
                </div>
            </div>
        </section>

    </div>

    @include('livewire.pos.partials.shift-summary-modal')

    @include('livewire.pos.partials.checkout-review-modal')
</div>
