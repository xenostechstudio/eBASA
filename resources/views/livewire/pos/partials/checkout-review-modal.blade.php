<x-modal name="pos-checkout-review" maxWidth="full">
    <div class="relative flex h-full w-full flex-col rounded-3xl bg-white/95 p-6 text-slate-900 shadow-2xl shadow-black/20 dark:bg-slate-950/95 dark:text-white dark:shadow-black/50"
        x-data="{
            total: {{ $paymentSummary['total'] }},
            paymentMethod: @js($paymentMethods[0]['label'] ?? ''),
            paymentAmount: '',
            showValidation: false,
            validationMessage: '',
            modalActive: false,
            skipNextEnter: false,
            closeValidation() {
                this.showValidation = false;
            },
            handleEnter(event) {
                if (!this.modalActive) {
                    return;
                }

                if (this.skipNextEnter) {
                    this.skipNextEnter = false;
                    return;
                }

                event?.preventDefault();
                event?.stopPropagation();

                if (this.showValidation) {
                    this.closeValidation();
                    return;
                }

                this.validatePayment();
            },
            validatePayment() {
                if (!this.paymentMethod) {
                    this.validationMessage = 'Silakan pilih metode pembayaran.';
                    this.showValidation = true;
                    return;
                }

                const paid = parseInt((this.paymentAmount || '').replace(/\D/g, '')) || 0;

                if (paid < this.total) {
                    this.validationMessage = 'Jumlah bayar belum mencukupi total.';
                    this.showValidation = true;
                    return;
                }

                this.validationMessage = 'Pembayaran valid. Lanjutkan untuk menyelesaikan transaksi.';
                this.showValidation = true;
            },
        }"
        @keydown.window.enter="handleEnter($event)"
        @modal-opened.window="if ($event.detail === 'pos-checkout-review') { modalActive = true; skipNextEnter = true; $nextTick(() => $refs.paymentAmountInput?.focus()); }"
        @modal-closed.window="if ($event.detail === 'pos-checkout-review') { modalActive = false; skipNextEnter = false; showValidation = false; validationMessage = ''; }">
        @include('livewire.pos.partials.validation-popup')
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-white/60">Checkout Review</p>
                <h2 class="mt-1 text-2xl font-semibold text-slate-900 dark:text-white">Confirm payment details</h2>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative">
                    <button type="button"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-50 dark:border-white/30 dark:bg-white/10 dark:text-white/80 dark:hover:bg-white/20"
                        @click="$dispatch('close-modal', 'pos-checkout-review')"
                        title="Cancel / Back">
                        {{ svg('heroicon-o-x-mark', 'w-7 h-7') }}
                    </button>
                    <span class="pointer-events-none absolute left-1/2 -top-2 -translate-x-1/2 rounded-full border border-slate-200 bg-white px-1.5 py-0.5 text-[9px] font-semibold uppercase tracking-[0.25em] text-slate-600 shadow-sm dark:border-white/30 dark:bg-slate-900 dark:text-white">Esc</span>
                </div>

                <div class="relative">
                    <button type="button"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 text-white shadow shadow-emerald-500/30 hover:bg-emerald-400"
                        title="Confirm charge"
                        @click="validatePayment()">
                        {{ svg('heroicon-o-check-badge', 'w-7 h-7') }}
                    </button>
                    <span class="pointer-events-none absolute left-1/2 -top-2 -translate-x-1/2 rounded-full border border-emerald-200/70 bg-white px-1.5 py-0.5 text-[9px] font-semibold uppercase tracking-[0.25em] text-emerald-700 shadow-sm dark:border-emerald-400/50 dark:bg-emerald-500/20 dark:text-emerald-50">Enter</span>
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-1 flex-col gap-5 overflow-hidden lg:flex-row">
            <section class="flex flex-1 flex-col gap-4 overflow-hidden">
                <div class="flex-none rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-slate-900/60">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-emerald-200/60 bg-white/80 text-emerald-600 dark:border-emerald-400/20 dark:bg-white/5 dark:text-emerald-200">
                            {{ svg('heroicon-o-identification', 'w-5 h-5') }}
                        </div>
                        <div class="flex-1">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-500 dark:text-white/50">Scan / Input Member</p>
                            <input type="text" class="mt-1 w-full border-none bg-transparent text-base font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none dark:text-white dark:placeholder:text-white/40" placeholder="Scan member card or type phone number">
                        </div>
                        <span class="rounded-xl border border-emerald-200/60 bg-emerald-300/20 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-900 dark:border-emerald-400/30 dark:bg-emerald-500/10 dark:text-emerald-50">F2</span>
                    </div>
                </div>

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

                <div class="mt-auto rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-slate-900/60">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-600 dark:text-white/60">Payment Method</p>

                    <div class="mt-3 flex gap-2">
                        @foreach ($paymentMethods as $method)
                            <button type="button"
                                class="flex flex-1 items-center justify-between gap-3 rounded-2xl border px-4 py-3 text-sm font-semibold transition focus:outline-none"
                                :class="paymentMethod === @js($method['label'])
                                    ? 'border-emerald-400 bg-emerald-50/80 text-emerald-900 shadow-inner shadow-emerald-500/10 dark:border-emerald-300/60 dark:bg-emerald-500/20 dark:text-white'
                                    : 'border-slate-200 bg-white text-slate-700 hover:border-emerald-200 dark:border-white/10 dark:bg-white/5 dark:text-white/80'"
                                @click="paymentMethod = @js($method['label'])">
                                <div class="flex items-center gap-2">
                                    <span>{{ $method['label'] }}</span>
                                    <span class="rounded-lg border px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.3em]"
                                        :class="paymentMethod === @js($method['label'])
                                            ? 'border-emerald-200 bg-emerald-100 text-emerald-900 dark:border-emerald-200/60 dark:bg-emerald-400/10 dark:text-white'
                                            : 'border-slate-200 bg-white text-slate-500 dark:border-white/20 dark:bg-white/5 dark:text-white/60'">
                                        {{ $method['shortcut'] ?? '' }}
                                    </span>
                                </div>
                                <svg class="h-5 w-5 text-emerald-500 transition"
                                    :class="paymentMethod === @js($method['label']) ? 'opacity-100' : 'opacity-0'"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 13 4 4L19 7" />
                                </svg>
                            </button>
                        @endforeach
                    </div>

                    {{-- Payment Amount Input --}}
                    <div class="mt-4 grid grid-cols-3 gap-3">
                        {{-- Total --}}
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-3 dark:border-emerald-400/30 dark:bg-emerald-500/10">
                            <p class="text-md font-semibold text-emerald-800 dark:text-emerald-200">Total</p>
                            <p class="mt-1 text-3xl font-bold tabular-nums text-emerald-900 dark:text-white">Rp {{ number_format($paymentSummary['total'], 0, ',', '.') }}</p>
                        </div>
                        {{-- Jumlah Bayar --}}
                        <div class="rounded-xl border border-slate-200 bg-white p-3 dark:border-white/10 dark:bg-white/5">
                            <div class="flex items-center justify-between">
                                <p class="text-md font-semibold text-slate-600 dark:text-white/70">Jumlah Bayar</p>
                                <span class="rounded-lg border border-slate-200 bg-white px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-500 dark:border-white/20 dark:bg-white/5 dark:text-white/60">F3</span>
                            </div>
                            <div class="mt-1 flex items-baseline gap-1">
                                <span class="text-base text-slate-400 dark:text-white/40">Rp</span>
                                <input type="text" x-model="paymentAmount" placeholder="0" x-ref="paymentAmountInput" class="w-full border-none bg-transparent p-0 text-3xl font-bold tabular-nums text-slate-900 placeholder:text-slate-300 focus:outline-none focus:ring-0 dark:text-white dark:placeholder:text-white/30">
                            </div>
                        </div>
                        {{-- Kembalian --}}
                        <div class="rounded-xl border border-slate-200 bg-white p-3 dark:border-white/10 dark:bg-white/5">
                            <p class="text-md font-semibold text-slate-600 dark:text-white/70">Kembalian</p>
                            <p class="mt-1 text-3xl font-bold tabular-nums"
                                :class="(parseInt(paymentAmount.replace(/\D/g, '')) || 0) >= total ? 'text-emerald-600 dark:text-emerald-300' : 'text-red-500 dark:text-red-400'"
                                x-text="(() => { const paid = parseInt(paymentAmount.replace(/\D/g, '')) || 0; const change = paid - total; return 'Rp ' + (change >= 0 ? change.toLocaleString('id-ID') : '(' + Math.abs(change).toLocaleString('id-ID') + ')'); })()">
                            </p>
                        </div>
                    </div>

                    <input type="hidden" name="payment_method" :value="paymentMethod">
                    <input type="hidden" name="payment_amount" :value="paymentAmount">
                </div>
            </section>

            <section class="relative flex w-full max-w-md flex-none flex-col rounded-3xl border border-slate-200 bg-white p-5 font-mono text-xs dark:border-white/10 dark:bg-slate-900">
                <button type="button" class="absolute right-4 top-4 inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:bg-slate-50 dark:border-white/10 dark:text-white/70 dark:hover:bg-white/10" title="Print receipt">
                    {{ svg('heroicon-o-printer', 'w-5 h-5') }}
                    <span class="pointer-events-none absolute -bottom-3 left-1/2 -translate-x-1/2 rounded-full border border-slate-200 bg-white px-1.5 py-0.5 text-[9px] font-semibold uppercase tracking-[0.25em] text-slate-600 shadow-sm dark:border-white/30 dark:bg-slate-900 dark:text-white">P</span>
                </button>
                {{-- Receipt Header --}}
                <div class="border-b-2 border-dashed border-slate-300 pb-3 text-center dark:border-white/20">
                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $shiftSummary['branch'] }}</p>
                    <p class="mt-1 text-slate-500 dark:text-white/60">Jl. Raya Tegal No. 123</p>
                    <p class="text-slate-500 dark:text-white/60">Telp: (0283) 123-4567</p>
                </div>

                {{-- Receipt Info --}}
                <div class="mt-3 flex items-center justify-between border-b border-dashed border-slate-300 pb-3 dark:border-white/20">
                    <div>
                        <p class="text-slate-500 dark:text-white/60">No: <span class="font-semibold text-slate-900 dark:text-white">#POS-{!! rand(3020, 3099) !!}</span></p>
                        <p class="text-slate-500 dark:text-white/60">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-slate-500 dark:text-white/60">Kasir:</p>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $shiftSummary['cashier'] }}</p>
                    </div>
                </div>

                {{-- Items List (scrollable) --}}
                <div class="mt-2 flex-1 space-y-0.5 overflow-y-auto pr-1">
                    @foreach ($cartItems as $item)
                        <div class="flex justify-between text-slate-700 dark:text-white/80">
                            <div class="flex-1 truncate pr-2">
                                <span class="text-slate-500 dark:text-white/60">{{ $item['qty'] }}x</span>
                                {{ Str::limit($item['name'], 24) }}
                            </div>
                            <span class="tabular-nums">{{ number_format($item['total'], 0, ',', '.') }}</span>
                        </div>
                        <div class="pl-4 text-[9px] text-slate-400 dark:text-white/40">
                            @ Rp {{ number_format($item['price'], 0, ',', '.') }}
                        </div>
                    @endforeach
                </div>

                {{-- Totals (sticky bottom) --}}
                <div class="mt-auto flex-none border-t-2 border-dashed border-slate-300 pt-3 dark:border-white/20">
                    <div class="flex justify-between text-slate-600 dark:text-white/70">
                        <span>Subtotal</span>
                        <span class="tabular-nums">{{ number_format($paymentSummary['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-emerald-600 dark:text-emerald-300">
                        <span>Discount</span>
                        <span class="tabular-nums">-{{ number_format($paymentSummary['discount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600 dark:text-white/70">
                        <span>Tax (10%)</span>
                        <span class="tabular-nums">{{ number_format($paymentSummary['tax'], 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-2 border-t border-dashed border-slate-300 pt-2 dark:border-white/20">
                        <div class="flex items-center justify-between text-base font-bold text-slate-900 dark:text-white">
                            <span>TOTAL</span>
                            <span class="tabular-nums">Rp {{ number_format($paymentSummary['total'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="mt-3 border-t border-dashed border-slate-300 pt-3 text-center text-[10px] text-slate-400 dark:border-white/20 dark:text-white/40">
                        <p>Terima kasih atas kunjungan Anda</p>
                        <p>Barang yang sudah dibeli tidak dapat</p>
                        <p>dikembalikan / ditukar</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-modal>

