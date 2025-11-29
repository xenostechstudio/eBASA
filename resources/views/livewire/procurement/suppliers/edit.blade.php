<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Procurement</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">Edit Supplier</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-white/60">Update supplier details and review related purchase orders.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('procurement.suppliers') }}"
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                Back to suppliers
            </a>
            <button wire:click="save"
                class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                @svg('heroicon-o-check', 'h-4 w-4')
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Supplier Form --}}
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Supplier Details</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Core information for this supplier.</p>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Name</label>
                        <input type="text" wire:model="form.name"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Code</label>
                        <input type="text" wire:model="form.code"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Contact Person</label>
                        <input type="text" wire:model="form.contact_name"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Email</label>
                        <input type="email" wire:model="form.email"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Phone</label>
                        <input type="text" wire:model="form.phone"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Tax Number</label>
                        <input type="text" wire:model="form.tax_number"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Payment Terms (days)</label>
                        <input type="number" min="0" wire:model="form.payment_terms"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div class="flex items-center gap-2 pt-6">
                        <span class="text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Active</span>
                        <button type="button" wire:click="$set('form.is_active', ! $form['is_active'])"
                            class="relative inline-flex h-6 w-11 items-center rounded-full border border-slate-300 bg-slate-200 transition dark:border-white/20 dark:bg-slate-800">
                            <span class="absolute inset-0 flex items-center justify-{{ $form['is_active'] ? 'end' : 'start' }} px-0.5">
                                <span class="h-5 w-5 rounded-full bg-white shadow transition"></span>
                            </span>
                        </button>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Address</label>
                        <textarea wire:model="form.address" rows="3"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Internal Notes</label>
                        <textarea wire:model="form.notes" rows="3"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Stats + Orders (relation manager style) --}}
        <div class="space-y-4 lg:col-span-1">
            {{-- Stats --}}
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-1">
                <x-stat.card label="Lifetime Spend" :value="'Rp ' . number_format($stats['lifetimeSpend'] / 1000000, 1) . 'M'" description="All time" tone="success">
                    <x-slot:icon>@svg('heroicon-o-banknotes', 'h-5 w-5 text-emerald-500')</x-slot:icon>
                </x-stat.card>

                <x-stat.card label="Orders" :value="number_format($stats['ordersCount'])" description="Total purchase orders" tone="neutral">
                    <x-slot:icon>@svg('heroicon-o-document-text', 'h-5 w-5 text-slate-500')</x-slot:icon>
                </x-stat.card>

                <x-stat.card label="Open Orders" :value="number_format($stats['openOrders'])" description="Waiting fulfillment" tone="warning">
                    <x-slot:icon>@svg('heroicon-o-clock', 'h-5 w-5 text-amber-500')</x-slot:icon>
                </x-stat.card>

                <x-stat.card label="On-time Delivery" :value="$stats['onTimeRate'] . '%'" description="Last 90 days" tone="info">
                    <x-slot:icon>@svg('heroicon-o-check-circle', 'h-5 w-5 text-sky-500')</x-slot:icon>
                </x-stat.card>
            </div>

            {{-- Relation Manager-like Orders Panel --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Purchase Orders</h3>
                        <p class="mt-0.5 text-xs text-slate-500 dark:text-white/50">Recent orders for this supplier</p>
                    </div>
                    <a href="{{ route('procurement.orders') }}" class="text-xs font-medium text-slate-500 hover:text-slate-900 dark:text-white/60 dark:hover:text-white">View all</a>
                </div>

                <div class="mt-4 space-y-2">
                    @forelse ($orders as $order)
                        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs dark:border-white/10 dark:bg-slate-950/40">
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white">{{ $order['number'] }}</p>
                                <p class="text-[11px] text-slate-500 dark:text-white/50">{{ $order['date']->format('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[11px] text-slate-500 dark:text-white/60">{{ ucfirst($order['status']) }}</p>
                                <p class="text-[11px] font-semibold text-slate-900 dark:text-white">Rp {{ number_format($order['amount'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 dark:text-white/60">No orders yet for this supplier.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
