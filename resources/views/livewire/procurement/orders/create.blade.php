<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Procurement</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">Create Purchase Order</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-white/60">Fill in the details to create a new purchase order.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('procurement.orders') }}"
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                Cancel
            </a>
            <button wire:click="save"
                class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                @svg('heroicon-o-check', 'h-4 w-4')
                <span>Create Order</span>
            </button>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Order Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Order Details</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Supplier, delivery, and reference details for this PO.</p>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Supplier</label>
                        <div class="mt-2">
                            <x-form.searchable-select
                                wire:model="supplier_id"
                                name="supplier_id"
                                placeholder="Search supplier..."
                                :options="$suppliers"
                                value-key="id"
                                label-key="name"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Deliver to</label>
                        <div class="mt-2">
                            <x-form.searchable-select
                                wire:model="delivery_warehouse_id"
                                name="delivery_warehouse_id"
                                placeholder="Search warehouse..."
                                :options="$warehouses->map(fn($w) => (object)['id' => $w->id, 'name' => $w->name, 'branch' => $w->branch?->name])"
                                value-key="id"
                                label-key="name"
                                sublabel-key="branch"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Expected delivery date</label>
                        <input type="date" wire:model="delivery_date"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Requested by</label>
                        <input type="text" wire:model="requested_by"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30"
                            placeholder="Requester name or department">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Payment terms</label>
                        <select wire:model="payment_terms"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            <option value="">Select terms</option>
                            <option value="cod">COD</option>
                            <option value="14_days">14 days</option>
                            <option value="30_days">30 days</option>
                            <option value="45_days">45 days</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Delivery instructions</label>
                        <textarea wire:model="delivery_instructions" rows="2"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30"
                            placeholder="Gate, contact person, unload time window, etc."></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Internal Notes</label>
                        <textarea wire:model="notes" rows="3"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30"
                            placeholder="Additional notes for your purchasing team..."></textarea>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Order Items</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Add products to this order</p>
                    </div>
                    <button wire:click="addItem"
                        class="inline-flex h-9 items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        <span>Add Item</span>
                    </button>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($items as $index => $item)
                        <div class="flex items-start gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-white/5">
                            <div class="flex-1 grid gap-4 md:grid-cols-3">
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-white/50">Product</label>
                                    <select wire:model="items.{{ $index }}.product_id"
                                        class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                                        <option value="">Select product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-white/50">Quantity</label>
                                    <input type="number" wire:model="items.{{ $index }}.quantity" min="1"
                                        class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-white/50">Unit Price</label>
                                    <input type="number" wire:model="items.{{ $index }}.price" min="0"
                                        class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                                </div>
                            </div>
                            <button wire:click="removeItem({{ $index }})"
                                class="mt-6 inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-rose-100 hover:text-rose-600 dark:hover:bg-rose-500/20 dark:hover:text-rose-400">
                                @svg('heroicon-o-trash', 'h-4 w-4')
                            </button>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            @svg('heroicon-o-shopping-cart', 'h-10 w-10 text-slate-300 dark:text-white/20')
                            <p class="mt-3 text-sm text-slate-500 dark:text-white/50">No items added yet</p>
                            <button wire:click="addItem"
                                class="mt-3 text-sm font-medium text-slate-900 hover:underline dark:text-white">
                                Add your first item
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Summary --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Order Summary</h3>

                <dl class="mt-6 space-y-4">
                    <div class="flex justify-between text-sm">
                        <dt class="text-slate-500 dark:text-white/60">Items</dt>
                        <dd class="font-medium text-slate-900 dark:text-white">{{ count($items) }}</dd>
                    </div>
                    <div class="flex justify-between text-sm">
                        <dt class="text-slate-500 dark:text-white/60">Total Quantity</dt>
                        <dd class="font-medium text-slate-900 dark:text-white">{{ collect($items)->sum('quantity') }}</dd>
                    </div>
                    <div class="border-t border-slate-200 pt-4 dark:border-white/10">
                        <div class="flex justify-between">
                            <dt class="text-base font-medium text-slate-900 dark:text-white">Total</dt>
                            <dd class="text-base font-bold text-slate-900 dark:text-white">
                                Rp {{ number_format(collect($items)->sum(fn($i) => ($i['quantity'] ?? 0) * ($i['price'] ?? 0)), 0, ',', '.') }}
                            </dd>
                        </div>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
