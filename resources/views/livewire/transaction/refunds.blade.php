<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-red-200 bg-red-50/50 p-6 dark:border-red-500/20 dark:bg-red-500/10">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-arrow-uturn-left', 'h-5 w-5 text-red-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-red-600 dark:text-red-400">Total Refunds</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['totalRefunds']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">All time refunded transactions</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-banknotes', 'h-5 w-5 text-amber-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Refund Amount</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">Rp {{ number_format($stats['totalRefundAmount'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Total refunded value</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-calendar', 'h-5 w-5 text-sky-500')
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Today</p>
            </div>
            <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['todayRefunds']) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Refunds processed today</p>
        </div>
    </div>

    {{-- Refunds Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Refund History</h2>
                    <p class="text-xs text-slate-500 dark:text-white/60">All refunded transactions</p>
                </div>
                <div class="relative">
                    @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search transaction..."
                        class="h-10 w-64 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                    >
                </div>
            </div>
        </div>

        @if ($refunds->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:text-white/60">
                            <th class="px-5 py-3">Transaction Code</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Branch</th>
                            <th class="px-5 py-3">Cashier</th>
                            <th class="px-5 py-3 text-right">Amount</th>
                            <th class="px-5 py-3">Refunded At</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($refunds as $refund)
                            <tr wire:click="viewTransaction({{ $refund->id }})" class="cursor-pointer transition hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="font-mono text-sm font-medium text-slate-900 dark:text-white">{{ $refund->transaction_code }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($refund->customer_name)
                                        <p class="text-sm text-slate-900 dark:text-white">{{ $refund->customer_name }}</p>
                                    @else
                                        <span class="text-sm text-slate-400 dark:text-white/40">Walk-in</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $refund->branch?->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $refund->cashier?->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <span class="text-sm font-semibold text-red-600 dark:text-red-400">-Rp {{ number_format($refund->total_amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $refund->updated_at->format('d M Y H:i') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4" wire:click.stop>
                                    <button wire:click="viewTransaction({{ $refund->id }})" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white" title="View Details">
                                        @svg('heroicon-o-eye', 'h-4 w-4')
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4 dark:border-white/10">
                {{ $refunds->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-arrow-uturn-left', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No refunds found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">Refunded transactions will appear here</p>
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
                        <span class="mt-2 inline-flex items-center rounded-lg bg-red-100 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-500/20 dark:text-red-400">
                            REFUNDED
                        </span>
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
                    <div class="flex items-center justify-between text-base font-bold text-red-600 dark:text-red-400">
                        <span>REFUNDED TOTAL</span>
                        <span class="tabular-nums">-Rp {{ number_format($selectedTransaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 dark:bg-white/10 dark:text-white/80">
                            {{ $selectedTransaction->payment_method_label }}
                        </span>
                        <p class="text-xs text-slate-500 dark:text-white/60">
                            Refunded: {{ $selectedTransaction->updated_at?->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
