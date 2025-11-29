<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Procurement</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">Return {{ $form['reference'] ?? '' }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-white/60">Review and update this supplier return.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('procurement.returns') }}"
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                Back to returns
            </a>
            <button wire:click="save"
                class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                @svg('heroicon-o-check', 'h-4 w-4')
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Left: Details & Items --}}
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Return Details</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Core information for this return.</p>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Reference</label>
                        <input type="text" disabled value="{{ $form['reference'] ?? '' }}"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Supplier</label>
                        <input type="text" disabled value="{{ $form['supplier_name'] ?? '' }}"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Status</label>
                        <select wire:model="form.status"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Created at</label>
                        <input type="text" disabled value="{{ optional($form['created_at'] ?? null)->format('d M Y H:i') }}"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Return Reason</label>
                        <input type="text" wire:model="form.reason"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Internal Notes</label>
                        <textarea wire:model="form.notes" rows="3"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30"></textarea>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Items</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Products included in this return.</p>

                <div class="mt-4 space-y-3">
                    @foreach ($items as $item)
                        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-white/10 dark:bg-slate-950/40">
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white">{{ $item['product'] }} <span class="text-xs text-slate-500 dark:text-white/60">({{ $item['sku'] }})</span></p>
                                <p class="text-xs text-slate-500 dark:text-white/60">{{ $item['reason'] }}</p>
                            </div>
                            <div class="text-right text-sm text-slate-700 dark:text-white/70">
                                {{ $item['quantity'] }} pcs
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right: Status timeline + related documents --}}
        <div class="space-y-4 lg:col-span-1">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Return status</h3>
                <p class="mt-1 text-xs text-slate-500 dark:text-white/50">Lifecycle of this return.</p>

                <ol class="mt-4 space-y-3 text-xs">
                    @foreach ($timeline as $step)
                        <li class="flex items-start gap-3">
                            <div class="mt-1 h-2 w-2 rounded-full {{ $step['status'] === 'done' ? 'bg-emerald-500' : ($step['status'] === 'current' ? 'bg-sky-500' : 'bg-slate-300') }}"></div>
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white">{{ $step['label'] }}</p>
                                <p class="text-[11px] text-slate-500 dark:text-white/60">{{ optional($step['time'])->format('d M Y H:i') }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Related documents</h3>
                        <p class="mt-1 text-xs text-slate-500 dark:text-white/50">Source order and goods receipt for this return.</p>
                    </div>
                    <a href="{{ route('procurement.orders') }}" class="text-xs font-medium text-slate-500 hover:text-slate-900 dark:text-white/60 dark:hover:text-white">View orders</a>
                </div>

                <div class="mt-4 space-y-3 text-xs">
                    @if (! empty($purchaseOrder))
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-white/10 dark:bg-slate-950/40">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">Purchase Order</p>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $purchaseOrder['number'] }}</p>
                                </div>
                                <span class="rounded-full bg-slate-900 px-2 py-0.5 text-[10px] font-medium uppercase tracking-wide text-white dark:bg-white dark:text-slate-900">
                                    {{ ucfirst(str_replace('_', ' ', $purchaseOrder['status'])) }}
                                </span>
                            </div>
                            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500 dark:text-white/60">
                                <span>{{ optional($purchaseOrder['date'] ?? null)->format('d M Y') }}</span>
                                <span>Rp {{ number_format($purchaseOrder['amount'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (! empty($goodsReceipt))
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 dark:border-white/10 dark:bg-slate-950/40">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">Goods Receipt</p>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $goodsReceipt['number'] }}</p>
                                </div>
                                <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-medium uppercase tracking-wide text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                                    {{ ucfirst($goodsReceipt['status']) }}
                                </span>
                            </div>
                            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500 dark:text-white/60">
                                <span>{{ optional($goodsReceipt['date'] ?? null)->format('d M Y') }}</span>
                                <span>{{ $goodsReceipt['received_items'] }} items received</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
