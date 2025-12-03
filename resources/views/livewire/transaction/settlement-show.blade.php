<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('transactions.settlements') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-white/70 dark:hover:border-white/20 dark:hover:bg-white/10">
            @svg('heroicon-o-arrow-left', 'h-4 w-4')
            <span>Back to Settlements</span>
        </a>

        <div class="text-right text-xs text-slate-500 dark:text-white/60">
            <p class="font-mono">Shift #{{ $shift->id }}</p>
            <p>
                {{ $shift->opened_at?->format('d M Y H:i') ?? '-' }}
                —
                {{ $shift->closed_at?->format('d M Y H:i') ?? '-' }}
            </p>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5 lg:col-span-2">
            <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400 dark:text-white/40">Shift Summary</p>
            <h2 class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">
                {{ $shift->branch?->name ?? '-' }}
            </h2>
            <p class="text-xs text-slate-500 dark:text-white/60">
                Cashier: <span class="font-medium text-slate-900 dark:text-white">{{ $shift->cashier?->name ?? '-' }}</span>
            </p>

            <div class="mt-4 grid grid-cols-3 gap-3 text-xs">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400 dark:text-white/40">Opening Cash</p>
                    <p class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                        Rp {{ number_format($shift->opening_cash ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400 dark:text-white/40">Expected Cash</p>
                    <p class="mt-1 text-sm font-medium text-slate-900 dark:text-white">
                        Rp {{ number_format($shift->expected_cash ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    @php
                        $diff = ($shift->closing_cash ?? 0) - ($shift->expected_cash ?? 0);
                    @endphp
                    <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400 dark:text-white/40">Actual Cash</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">
                        Rp {{ number_format($shift->closing_cash ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="mt-1 text-[11px] font-medium {{ $diff === 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                        {{ $diff === 0 ? 'Balanced' : 'Diff: '.($diff >= 0 ? '+' : '').'Rp '.number_format($diff, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 text-xs dark:border-white/10 dark:bg-white/5">
            <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400 dark:text-white/40">Transactions</p>
            <div class="mt-3 space-y-1">
                <p class="flex items-center justify-between">
                    <span>Total Transactions</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $totals['transactionCount'] }}</span>
                </p>
                <p class="flex items-center justify-between">
                    <span>Gross Sales</span>
                    <span class="font-semibold text-slate-900 dark:text-white">Rp {{ number_format($totals['grossSales'], 0, ',', '.') }}</span>
                </p>
                <p class="flex items-center justify-between">
                    <span>Cash Sales</span>
                    <span class="font-semibold text-slate-900 dark:text-white">Rp {{ number_format($totals['cashSales'], 0, ',', '.') }}</span>
                </p>
                <p class="flex items-center justify-between">
                    <span>Non-cash Sales</span>
                    <span class="font-semibold text-slate-900 dark:text-white">Rp {{ number_format($totals['nonCashSales'], 0, ',', '.') }}</span>
                </p>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 text-xs dark:border-white/10 dark:bg-white/5">
            <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400 dark:text-white/40">Shift Timing</p>
            <div class="mt-3 space-y-1">
                <p class="flex items-center justify-between">
                    <span>Opened At</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ $shift->opened_at?->format('d M Y H:i') ?? '-' }}</span>
                </p>
                <p class="flex items-center justify-between">
                    <span>Closed At</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ $shift->closed_at?->format('d M Y H:i') ?? '-' }}</span>
                </p>
                <p class="flex items-center justify-between">
                    <span>Duration</span>
                    <span class="font-medium text-slate-900 dark:text-white">
                        @if ($shift->opened_at && $shift->closed_at)
                            {{ $shift->opened_at->diffForHumans($shift->closed_at, true) }}
                        @else
                            -
                        @endif
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Transactions in this shift</h2>
                    <p class="text-xs text-slate-500 dark:text-white/60">Click a row to view receipt details</p>
                </div>
                <p class="text-xs text-slate-500 dark:text-white/60">
                    {{ $totals['transactionCount'] }} transactions · Gross Rp {{ number_format($totals['grossSales'], 0, ',', '.') }}
                </p>
            </div>
        </div>

        @if ($transactions->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-receipt-percent', 'h-10 w-10 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No transactions recorded for this shift.</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">Transactions created from POS will appear here.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:text-white/60">
                            <th class="px-5 py-3">Code</th>
                            <th class="px-5 py-3">Time</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Payment</th>
                            <th class="px-5 py-3">Items</th>
                            <th class="px-5 py-3 text-right">Amount</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($transactions as $transaction)
                            <tr wire:click="viewTransaction({{ $transaction->id }})" class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="font-mono text-sm font-medium text-slate-900 dark:text-white">{{ $transaction->transaction_code }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $transaction->created_at?->format('H:i') ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $transaction->customer_name ?? 'Walk-in' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 dark:bg-white/10 dark:text-white/80">
                                        {{ $transaction->payment_method_label }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $transaction->items->count() }} items
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <span class="text-sm font-semibold text-slate-900 dark:text-white">Rp {{ number_format($transaction->total_amount ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @php
                                        $statusColors = [
                                            'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                                            'cancelled' => 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-white/60',
                                            'refunded' => 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $statusColors[$transaction->status] ?? $statusColors['pending'] }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Transaction Detail Modal --}}
    @if ($selectedTransaction)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4" role="dialog" aria-modal="true">
            <div wire:click="closeTransactionDetail" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity dark:bg-slate-950/70"></div>

            <div class="relative z-10 flex w-full max-w-lg flex-col rounded-3xl bg-white text-slate-900 shadow-2xl dark:bg-slate-900 dark:text-white">
                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-white/10">
                    <div>
                        <p class="font-mono text-sm font-semibold text-slate-900 dark:text-white">{{ $selectedTransaction->transaction_code }}</p>
                        <p class="text-xs text-slate-500 dark:text-white/60">{{ $selectedTransaction->created_at?->format('d M Y H:i') }}</p>
                    </div>
                    <button wire:click="closeTransactionDetail" class="rounded-full p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white">
                        @svg('heroicon-o-x-mark', 'h-5 w-5')
                    </button>
                </div>

                {{-- Receipt Content --}}
                <div class="flex-1 overflow-y-auto p-6 font-mono text-xs">
                    {{-- Store Info --}}
                    <div class="border-b-2 border-dashed border-slate-300 pb-3 text-center dark:border-white/20">
                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $selectedTransaction->branch?->name ?? '-' }}</p>
                        <p class="mt-1 text-slate-500 dark:text-white/60">Cashier: {{ $selectedTransaction->cashier?->name ?? '-' }}</p>
                    </div>

                    {{-- Items --}}
                    <div class="mt-4 max-h-48 space-y-1 overflow-y-auto border-b border-dashed border-slate-300 pb-4 dark:border-white/20">
                        @forelse ($selectedTransaction->items as $item)
                            <div class="flex justify-between text-slate-700 dark:text-white/80">
                                <div class="flex-1 truncate pr-2">
                                    <span class="text-slate-500 dark:text-white/60">{{ $item->quantity }}x</span>
                                    {{ Str::limit($item->product_name, 28) }}
                                </div>
                                <span class="tabular-nums">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="pl-4 text-[10px] text-slate-400 dark:text-white/40">
                                @ Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </div>
                        @empty
                            <p class="text-center text-slate-400 dark:text-white/40">No items recorded</p>
                        @endforelse
                    </div>

                    {{-- Totals --}}
                    <div class="mt-4 space-y-1">
                        <div class="flex justify-between text-slate-600 dark:text-white/70">
                            <span>Subtotal</span>
                            <span class="tabular-nums">{{ number_format($selectedTransaction->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-emerald-600 dark:text-emerald-300">
                            <span>Discount</span>
                            <span class="tabular-nums">-{{ number_format($selectedTransaction->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600 dark:text-white/70">
                            <span>Tax</span>
                            <span class="tabular-nums">{{ number_format($selectedTransaction->tax_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Footer with Total --}}
                <div class="border-t border-slate-200 px-6 py-4 dark:border-white/10">
                    <div class="flex items-center justify-between text-base font-bold text-slate-900 dark:text-white">
                        <span>TOTAL</span>
                        <span class="tabular-nums">Rp {{ number_format($selectedTransaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-2 flex justify-between text-xs text-slate-500 dark:text-white/60">
                        <span>Paid</span>
                        <span class="tabular-nums">Rp {{ number_format($selectedTransaction->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500 dark:text-white/60">
                        <span>Change</span>
                        <span class="tabular-nums">Rp {{ number_format($selectedTransaction->change_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 dark:bg-white/10 dark:text-white/80">
                            {{ $selectedTransaction->payment_method_label }}
                        </span>
                        @php
                            $statusColors = [
                                'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                                'cancelled' => 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-white/60',
                                'refunded' => 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400',
                            ];
                        @endphp
                        <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-medium {{ $statusColors[$selectedTransaction->status] ?? $statusColors['pending'] }}">
                            {{ ucfirst($selectedTransaction->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
