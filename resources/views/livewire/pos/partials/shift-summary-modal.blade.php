<x-modal name="pos-shift-summary" maxWidth="7xl">
    <div class="flex h-full flex-col rounded-3xl bg-white/95 p-6 text-slate-900 shadow-2xl shadow-black/20 dark:bg-slate-950/95 dark:text-white dark:shadow-black/50"
        x-data="{
            selected: @js($cashierTransactions[0] ?? null),
            select(tx) {
                this.selected = tx
            },
            printShiftSummary() {
                const printable = this.$refs.shiftSummaryPrint;
                if (!printable) {
                    return;
                }

                const printWindow = window.open('', '', 'width=400,height=600');
                if (!printWindow) {
                    return;
                }

                const styles = `
                    @page { size: 80mm auto; margin: 5mm; }
                    * { box-sizing: border-box; }
                    body { font-family: 'JetBrains Mono', 'Courier New', monospace; width: 72mm; margin: 0 auto; padding: 0; color: #0f172a; background: #fff; }
                    .shift-print { width: 100%; font-size: 11px; line-height: 1.35; }
                    .shift-print__store { font-weight: 700; font-size: 14px; text-align: center; margin: 0 0 2px; }
                    .shift-print__title { text-align: center; text-transform: uppercase; letter-spacing: 0.35em; font-size: 10px; margin: 0; }
                    .shift-print__date { text-align: center; font-size: 10px; margin: 2px 0 8px; color: #94a3b8; }
                    .shift-print__meta { text-align: center; font-size: 10px; margin: 0; color: #475569; }
                    .shift-print__divider { border-top: 1px dashed #cbd5ff; margin: 10px 0; }
                    .shift-print__kpis { display: flex; flex-direction: column; gap: 4px; }
                    .shift-print__kpi { display: flex; justify-content: space-between; font-size: 11px; }
                    .shift-print__kpi span:last-child { font-weight: 600; }
                    .shift-print__section { font-size: 10px; text-transform: uppercase; letter-spacing: 0.3em; text-align: center; margin-bottom: 4px; color: #64748b; }
                    .shift-print__transactions { display: flex; flex-direction: column; gap: 6px; }
                    .shift-print__transaction { display: flex; justify-content: space-between; gap: 8px; }
                    .shift-print__transaction-number { margin: 0; font-weight: 600; }
                    .shift-print__transaction-meta { font-size: 9px; color: #94a3b8; margin: 0; }
                    .shift-print__transaction-amount { text-align: right; font-size: 10px; }
                    .shift-print__transaction-amount p { margin: 0; }
                    .shift-print__footer { text-align: center; font-size: 9px; margin-top: 10px; color: #94a3b8; }
                `;

                printWindow.document.write(`<!DOCTYPE html><html><head><title>Shift Summary</title><style>${styles}</style></head><body>${printable.innerHTML}</body></html>`);
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }
        }">
        <div class="flex flex-none flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-500 dark:text-white/60">Shift Summary</p>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:border-white/10 dark:text-white/80 dark:hover:bg-white/10"
                    @click="printShiftSummary()">
                    {{ svg('heroicon-o-printer', 'w-4 h-4') }}
                    Print Shift Summary
                </button>
                <button type="button" x-data class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-white/10 dark:text-white/70 dark:hover:bg-white/5"
                    @click="$dispatch('close-modal', 'pos-shift-summary')">
                    Close
                </button>
            </div>
        </div>

        <div class="mt-6 flex-none grid gap-3 lg:grid-cols-3">
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

        <div class="mt-6 flex flex-1 gap-5 overflow-hidden lg:grid lg:grid-cols-12">
            <div class="flex flex-col rounded-3xl border border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-slate-900/40 lg:col-span-7">
                <div class="grid grid-cols-12 gap-3 px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-white/60">
                    <p class="col-span-4">Transaction</p>
                    <p class="col-span-2">Time</p>
                    <p class="col-span-2 text-center">Items</p>
                    <p class="col-span-2">Channel</p>
                    <p class="col-span-2 text-right">Amount</p>
                </div>
                <div class="flex-1 divide-y divide-slate-200 overflow-y-auto dark:divide-white/5">
                    @foreach ($cashierTransactions as $transaction)
                        <button type="button"
                            class="grid w-full grid-cols-12 gap-3 px-5 py-3 text-left text-sm text-slate-700 transition hover:bg-white dark:text-white/80 dark:hover:bg-white/5"
                            :class="selected?.number === @js($transaction['number']) ? 'bg-white shadow-inner dark:bg-white/10' : ''"
                            @click="select(@js($transaction))">
                            <p class="col-span-4 font-semibold text-slate-900 dark:text-white">{{ $transaction['number'] }}</p>
                            <p class="col-span-2 text-slate-500 dark:text-white/60">{{ $transaction['time'] }}</p>
                            <p class="col-span-2 text-center tabular-nums">{{ $transaction['items'] }}</p>
                            <p class="col-span-2 text-slate-600 dark:text-white/80">{{ $transaction['channel'] }}</p>
                            <p class="col-span-2 text-right font-semibold tabular-nums text-slate-900 dark:text-white">Rp {{ number_format($transaction['amount'], 0, ',', '.') }}</p>
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="relative flex h-full flex-col rounded-3xl border border-slate-200 bg-white p-5 font-mono text-xs shadow-sm dark:border-white/10 dark:bg-slate-900 lg:col-span-5">
                <button type="button" class="absolute right-4 top-4 inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:bg-slate-50 dark:border-white/10 dark:text-white/70 dark:hover:bg-white/10" title="Print receipt">
                    {{ svg('heroicon-o-printer', 'w-5 h-5') }}
                    <span class="pointer-events-none absolute -bottom-3 left-1/2 -translate-x-1/2 rounded-full border border-slate-200 bg-white px-1.5 py-0.5 text-[9px] font-semibold uppercase tracking-[0.25em] text-slate-600 shadow-sm dark:border-white/30 dark:bg-slate-900 dark:text-white">P</span>
                </button>
                <template x-if="selected">
                    <div class="flex h-full flex-col">
                        <div class="border-b-2 border-dashed border-slate-300 pb-3 text-center dark:border-white/20">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $shiftSummary['branch'] }}</p>
                            <p class="mt-1 text-slate-500 dark:text-white/60">Jl. Raya Tegal No. 123</p>
                            <p class="text-slate-500 dark:text-white/60">Telp: (0283) 123-4567</p>
                        </div>
                        <div class="mt-3 flex items-center justify-between border-b border-dashed border-slate-300 pb-3 text-[11px] text-slate-500 dark:border-white/20 dark:text-white/60">
                            <div>
                                <p>No: <span class="font-semibold text-slate-900 dark:text-white" x-text="selected.number"></span></p>
                                <p x-text="selected.time"></p>
                            </div>
                            <div class="text-right">
                                <p>Kasir:</p>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $shiftSummary['cashier'] }}</p>
                            </div>
                        </div>
                        <div class="mt-3 flex-1 space-y-1.5 overflow-y-auto pr-1">
                            <template x-for="item in selected.receipt?.items ?? []" :key="item.name">
                                <div>
                                    <div class="flex justify-between text-slate-700 dark:text-white/80">
                                        <div class="flex-1 truncate pr-2">
                                            <span class="text-slate-500" x-text="item.qty + 'x'"></span>
                                            <span x-text="item.name"></span>
                                        </div>
                                        <span class="tabular-nums" x-text="item.total.toLocaleString('id-ID')"></span>
                                    </div>
                                    <p class="pl-4 text-[10px] text-slate-400 dark:text-white/40" x-text="'@ Rp ' + item.price.toLocaleString('id-ID')"></p>
                                </div>
                            </template>
                        </div>
                        <div class="mt-4 border-t-2 border-dashed border-slate-300 pt-3 dark:border-white/20">
                            <div class="flex justify-between text-slate-600 dark:text-white/70">
                                <span>Subtotal</span>
                                <span class="tabular-nums" x-text="selected.receipt?.subtotal?.toLocaleString('id-ID') ?? '-'" ></span>
                            </div>
                            <div class="flex justify-between text-emerald-600 dark:text-emerald-300">
                                <span>Discount</span>
                                <span class="tabular-nums" x-text="selected.receipt?.discount ? '-' + selected.receipt.discount.toLocaleString('id-ID') : '-' "></span>
                            </div>
                            <div class="flex justify-between text-slate-600 dark:text-white/70">
                                <span>Tax</span>
                                <span class="tabular-nums" x-text="selected.receipt?.tax?.toLocaleString('id-ID') ?? '-'" ></span>
                            </div>
                            <div class="mt-2 border-t border-dashed border-slate-300 pt-2 dark:border-white/20">
                                <div class="flex items-center justify-between text-base font-bold text-slate-900 dark:text-white">
                                    <span>Total</span>
                                    <span class="tabular-nums">
                                        <span class="text-sm font-normal text-slate-500 dark:text-white/60" x-text="selected.channel ? selected.channel + ' ' : ''"></span>
                                        <span x-text="'Rp ' + (selected.receipt?.total?.toLocaleString('id-ID') ?? '-')"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="mt-3 border-t border-dashed border-slate-300 pt-3 text-center text-[10px] text-slate-400 dark:border-white/20 dark:text-white/40">
                                <p>Terima kasih atas kunjungan Anda</p>
                                <p>Barang yang sudah dibeli tidak dapat</p>
                                <p>dikembalikan / ditukar</p>
                            </div>
                        </div>
                    </div>
                </template>
                <template x-if="!selected">
                    <p class="text-center text-slate-500 dark:text-white/50">Select a transaction to view its receipt.</p>
                </template>
            </div>
        </div>
        <div x-ref="shiftSummaryPrint" class="hidden">
            @php($shiftSummaryPrintedAt = now()->timezone('Asia/Jakarta')->translatedFormat('d MMM yyyy, HH:mm'))
            <div class="shift-print">
                <p class="shift-print__store">{{ $shiftSummary['branch'] }}</p>
                <p class="shift-print__title">Shift Summary</p>
                <p class="shift-print__date">Dicetak {{ $shiftSummaryPrintedAt }}</p>
                <p class="shift-print__meta">Kasir: {{ $shiftSummary['cashier'] }}</p>
                <p class="shift-print__meta">Shift dimulai {{ $shiftSummary['since'] }}</p>
                <div class="shift-print__divider"></div>
                <div class="shift-print__kpis">
                    <div class="shift-print__kpi">
                        <span>Sales</span>
                        <span>Rp {{ number_format($shiftSummary['sales'], 0, ',', '.') }}</span>
                    </div>
                    <div class="shift-print__kpi">
                        <span>Transactions</span>
                        <span>{{ $shiftSummary['transactions'] }}</span>
                    </div>
                    <div class="shift-print__kpi">
                        <span>Cash on Hand</span>
                        <span>Rp {{ number_format($shiftSummary['cashOnHand'], 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="shift-print__divider"></div>
                <p class="shift-print__section">Transactions</p>
                <div class="shift-print__transactions">
                    @foreach ($cashierTransactions as $transaction)
                        <div class="shift-print__transaction">
                            <div>
                                <p class="shift-print__transaction-number">{{ $transaction['number'] }}</p>
                                <p class="shift-print__transaction-meta">{{ $transaction['time'] }} • {{ $transaction['items'] }} item{{ $transaction['items'] > 1 ? 's' : '' }}</p>
                            </div>
                            <div class="shift-print__transaction-amount">
                                <p>{{ $transaction['channel'] }}</p>
                                <p>Rp {{ number_format($transaction['amount'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="shift-print__divider"></div>
                <div class="shift-print__footer">
                    <p>Terima kasih, simpan struk ini sebagai arsip shift.</p>
                </div>
            </div>
        </div>
    </div>
</x-modal>
