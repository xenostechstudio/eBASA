<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid gap-4 md:grid-cols-5">
        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-slate-300 hover:shadow-sm dark:border-white/10 dark:bg-white/5 dark:hover:border-white/20">
            <div class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-slate-100/50 dark:bg-white/5"></div>
            <div class="relative">
                <div class="flex items-center gap-2">
                    @svg('heroicon-o-currency-dollar', 'h-4 w-4 text-slate-400 dark:text-white/40')
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Today's Sales</p>
                </div>
                <p class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Total revenue today</p>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5 transition hover:border-emerald-300 hover:shadow-sm dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:hover:border-emerald-500/30">
            <div class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-emerald-100/50 dark:bg-emerald-500/10"></div>
            <div class="relative">
                <div class="flex items-center gap-2">
                    @svg('heroicon-o-check-circle', 'h-4 w-4 text-emerald-500 dark:text-emerald-400')
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-400">Completed</p>
                </div>
                <p class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($completedCount) }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Successful transactions</p>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl border border-amber-200 bg-amber-50/50 p-5 transition hover:border-amber-300 hover:shadow-sm dark:border-amber-500/20 dark:bg-amber-500/10 dark:hover:border-amber-500/30">
            <div class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-amber-100/50 dark:bg-amber-500/10"></div>
            <div class="relative">
                <div class="flex items-center gap-2">
                    @svg('heroicon-o-clock', 'h-4 w-4 text-amber-500 dark:text-amber-400')
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">Pending</p>
                </div>
                <p class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($pendingCount) }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Awaiting completion</p>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl border border-red-200 bg-red-50/50 p-5 transition hover:border-red-300 hover:shadow-sm dark:border-red-500/20 dark:bg-red-500/10 dark:hover:border-red-500/30">
            <div class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-red-100/50 dark:bg-red-500/10"></div>
            <div class="relative">
                <div class="flex items-center gap-2">
                    @svg('heroicon-o-arrow-uturn-left', 'h-4 w-4 text-red-500 dark:text-red-400')
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-red-600 dark:text-red-400">Refunded</p>
                </div>
                <p class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($refundedCount) }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Returned transactions</p>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl border border-sky-200 bg-sky-50/50 p-5 transition hover:border-sky-300 hover:shadow-sm dark:border-sky-500/20 dark:bg-sky-500/10 dark:hover:border-sky-500/30">
            <div class="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-sky-100/50 dark:bg-sky-500/10"></div>
            <div class="relative">
                <div class="flex items-center gap-2">
                    @svg('heroicon-o-user-group', 'h-4 w-4 text-sky-500 dark:text-sky-400')
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-sky-600 dark:text-sky-400">Open Shifts</p>
                </div>
                <p class="mt-3 text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($openShifts) }}</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-white/60">Active cashiers</p>
            </div>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b border-slate-100 px-5 py-4 dark:border-white/10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">All Transactions</h2>
                    <p class="text-xs text-slate-500 dark:text-white/60">POS activity from all branches</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    {{-- Search --}}
                    <div class="relative">
                        @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search code, customer..."
                            class="h-10 w-64 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                        >
                    </div>

                    {{-- Status Filter --}}
                    {{-- Payment Filter --}}
                    <x-table.dynamic-filters :filters="[
                        'status' => [
                            'label' => 'Status',
                            'options' => $statusOptions,
                            'selected' => $statusFilter,
                            'default' => '',
                            'onSelect' => 'setStatusFilter',
                        ],
                        'payment' => [
                            'label' => 'Payment',
                            'options' => $paymentOptions,
                            'selected' => $paymentFilter,
                            'default' => '',
                            'onSelect' => 'setPaymentFilter',
                        ],
                    ]" />

                    {{-- Export Dropdown --}}
                    <x-table.export-dropdown
                        aria-label="Export transactions"
                    />
                </div>
            </div>
        </div>

        @if ($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-white/10 dark:text-white/60">
                            <th class="px-5 py-3">
                                <button wire:click="sortBy('transaction_code')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    Code
                                    @if ($sortField === 'transaction_code')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">Branch</th>
                            <th class="px-5 py-3">Cashier</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Payment</th>
                            <th class="px-5 py-3 text-right">
                                <button wire:click="sortBy('total_amount')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white ml-auto">
                                    Amount
                                    @if ($sortField === 'total_amount')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">
                                <button wire:click="sortBy('created_at')" class="flex items-center gap-1 hover:text-slate-700 dark:hover:text-white">
                                    Date
                                    @if ($sortField === 'created_at')
                                        @svg($sortDirection === 'asc' ? 'heroicon-s-chevron-up' : 'heroicon-s-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                        @foreach ($transactions as $transaction)
                            <tr class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="font-mono text-sm font-medium text-slate-900 dark:text-white">{{ $transaction->transaction_code }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $transaction->branch?->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600 dark:text-white/70">
                                    {{ $transaction->cashier?->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($transaction->customer_name)
                                        <p class="text-sm text-slate-900 dark:text-white">{{ $transaction->customer_name }}</p>
                                        @if ($transaction->customer_phone)
                                            <p class="text-xs text-slate-500 dark:text-white/50">{{ $transaction->customer_phone }}</p>
                                        @endif
                                    @else
                                        <span class="text-sm text-slate-400 dark:text-white/40">Walk-in</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 dark:bg-white/10 dark:text-white/80">
                                        {{ $transaction->payment_method_label }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <span class="text-sm font-semibold text-slate-900 dark:text-white">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
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
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500 dark:text-white/60">
                                    {{ $transaction->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center justify-end gap-1">
                                        <button
                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white"
                                            title="View Details"
                                        >
                                            @svg('heroicon-o-eye', 'h-4 w-4')
                                        </button>
                                        <button
                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white"
                                            title="Print Receipt"
                                        >
                                            @svg('heroicon-o-printer', 'h-4 w-4')
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="border-t border-slate-100 px-5 py-4 dark:border-white/10">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                @svg('heroicon-o-receipt-percent', 'h-12 w-12 text-slate-300 dark:text-white/20')
                <p class="mt-4 text-sm font-medium text-slate-500 dark:text-white/60">No transactions found</p>
                <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                    @if ($search || $statusFilter || $paymentFilter)
                        Try adjusting your search or filters
                    @else
                        Transactions will appear here once recorded from POS
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
