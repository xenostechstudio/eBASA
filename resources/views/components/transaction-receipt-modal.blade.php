@props([
    'transaction' => null,
    'closeAction' => 'closeTransactionDetail',
])

@if ($transaction)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4" role="dialog" aria-modal="true">
        <div wire:click="{{ $closeAction }}" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity dark:bg-slate-950/70"></div>

        <div class="relative z-10 flex w-full max-w-md flex-col rounded-3xl bg-white text-slate-900 shadow-2xl dark:bg-slate-900 dark:text-white">
            {{-- Header with Print Button --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-white/10">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-white/60">Receipt</p>
                    <p class="font-mono text-sm font-semibold text-slate-900 dark:text-white">{{ $transaction->transaction_code }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="rounded-xl border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700 dark:border-white/10 dark:text-white/60 dark:hover:bg-white/10 dark:hover:text-white" title="Print receipt">
                        @svg('heroicon-o-printer', 'h-4 w-4')
                    </button>
                    <button wire:click="{{ $closeAction }}" class="rounded-xl border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700 dark:border-white/10 dark:text-white/60 dark:hover:bg-white/10 dark:hover:text-white">
                        @svg('heroicon-o-x-mark', 'h-4 w-4')
                    </button>
                </div>
            </div>

            {{-- Receipt Content --}}
            <div class="flex-1 overflow-y-auto p-5 font-mono text-xs">
                {{-- Store Header --}}
                <div class="border-b-2 border-dashed border-slate-300 pb-4 text-center dark:border-white/20">
                    <p class="text-base font-bold text-slate-900 dark:text-white">{{ $transaction->branch?->name ?? 'BASA Store' }}</p>
                    <p class="mt-1 text-slate-500 dark:text-white/60">{{ $transaction->branch?->address ?? 'Jl. Raya No. 123' }}</p>
                    <p class="text-slate-500 dark:text-white/60">Telp: {{ $transaction->branch?->phone ?? '(0283) 123-4567' }}</p>
                </div>

                {{-- Transaction Info --}}
                <div class="mt-3 flex items-center justify-between border-b border-dashed border-slate-300 pb-3 text-[11px] text-slate-500 dark:border-white/20 dark:text-white/60">
                    <div>
                        <p>No: <span class="font-semibold text-slate-900 dark:text-white">{{ $transaction->transaction_code }}</span></p>
                        <p>{{ $transaction->created_at?->format('d M Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p>Kasir:</p>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $transaction->cashier?->name ?? '-' }}</p>
                    </div>
                </div>

                {{-- Items --}}
                <div class="mt-3 max-h-52 space-y-2 overflow-y-auto pr-1">
                    @forelse ($transaction->items as $item)
                        <div>
                            <div class="flex justify-between text-slate-700 dark:text-white/80">
                                <div class="flex-1 truncate pr-2">
                                    <span class="text-slate-500 dark:text-white/60">{{ $item->quantity }}x</span>
                                    <span>{{ Str::limit($item->product_name, 24) }}</span>
                                </div>
                                <span class="tabular-nums">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <p class="pl-4 text-[10px] text-slate-400 dark:text-white/40">@ Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                        </div>
                    @empty
                        <p class="py-4 text-center text-slate-400 dark:text-white/40">No items recorded</p>
                    @endforelse
                </div>

                {{-- Totals --}}
                <div class="mt-4 border-t-2 border-dashed border-slate-300 pt-3 dark:border-white/20">
                    <div class="flex justify-between text-slate-600 dark:text-white/70">
                        <span>Subtotal</span>
                        <span class="tabular-nums">{{ number_format($transaction->subtotal ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-emerald-600 dark:text-emerald-300">
                        <span>Discount</span>
                        <span class="tabular-nums">-{{ number_format($transaction->discount_amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600 dark:text-white/70">
                        <span>Tax</span>
                        <span class="tabular-nums">{{ number_format($transaction->tax_amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-2 border-t border-dashed border-slate-300 pt-2 dark:border-white/20">
                        <div class="flex items-center justify-between text-base font-bold text-slate-900 dark:text-white">
                            <span>Total</span>
                            <span class="tabular-nums">
                                <span class="mr-1 text-xs font-normal text-slate-500 dark:text-white/60">{{ $transaction->payment_method_label }}</span>
                                Rp {{ number_format($transaction->total_amount ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Payment Details --}}
                <div class="mt-3 space-y-1 border-t border-dashed border-slate-300 pt-3 dark:border-white/20">
                    <div class="flex justify-between text-slate-600 dark:text-white/70">
                        <span>Paid</span>
                        <span class="tabular-nums">Rp {{ number_format($transaction->paid_amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600 dark:text-white/70">
                        <span>Change</span>
                        <span class="tabular-nums">Rp {{ number_format($transaction->change_amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="mt-4 border-t border-dashed border-slate-300 pt-3 text-center text-[10px] text-slate-400 dark:border-white/20 dark:text-white/40">
                    <p>Terima kasih atas kunjungan Anda</p>
                    <p>Barang yang sudah dibeli tidak dapat</p>
                    <p>dikembalikan / ditukar</p>
                </div>
            </div>

            {{-- Status Footer --}}
            <div class="flex items-center justify-between border-t border-slate-200 px-5 py-3 dark:border-white/10">
                @php
                    $statusColors = [
                        'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                        'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                        'cancelled' => 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-white/60',
                        'refunded' => 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400',
                    ];
                @endphp
                <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold {{ $statusColors[$transaction->status] ?? $statusColors['pending'] }}">
                    {{ ucfirst($transaction->status) }}
                </span>
                @if ($transaction->customer_name)
                    <p class="text-xs text-slate-500 dark:text-white/60">
                        Customer: <span class="font-medium text-slate-700 dark:text-white/80">{{ $transaction->customer_name }}</span>
                    </p>
                @else
                    <p class="text-xs text-slate-400 dark:text-white/40">Walk-in customer</p>
                @endif
            </div>
        </div>
    </div>
@endif
