<div>
    @if (session()->has('status'))
        <x-alert type="success">
            {{ session('status') }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Procurement</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">Add Supplier</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/60">Register a new vendor for purchase orders.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('procurement.suppliers') }}"
                    class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                    Cancel
                </a>
                <button wire:click="save"
                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                    @svg('heroicon-o-check', 'h-4 w-4')
                    <span>Save Supplier</span>
                </button>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
        {{-- Supplier Form --}}
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Supplier Details</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Basic information used across procurement.</p>

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

        {{-- Side Stats --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Supplier Impact (sample)</h3>
                <p class="mt-1 text-xs text-slate-500 dark:text-white/50">These numbers are illustrative for now.</p>

                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500 dark:text-white/60">Total suppliers</dt>
                        <dd class="font-medium text-slate-900 dark:text-white">{{ number_format($stats['totalSuppliers']) }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500 dark:text-white/60">Active</dt>
                        <dd class="font-medium text-slate-900 dark:text-white">{{ number_format($stats['active']) }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500 dark:text-white/60">Avg. lead time</dt>
                        <dd class="font-medium text-slate-900 dark:text-white">{{ $stats['avgLeadTime'] }} days</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
