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
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Supplier <span class="text-rose-500">*</span></label>
                        <select wire:model.live="supplier_id"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            <option value="">Select supplier...</option>
                            @foreach($this->suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->code }})</option>
                            @endforeach
                        </select>
                        @error('supplier_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        @if($supplier_id)
                            @php $selectedSupplier = $this->suppliers->firstWhere('id', $supplier_id); @endphp
                            @if($selectedSupplier)
                                <p class="mt-1 text-xs text-slate-500 dark:text-white/50">
                                    {{ $selectedSupplier->products()->count() }} products available from this supplier
                                </p>
                            @endif
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Deliver to Warehouse</label>
                        <select wire:model="warehouse_id"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            <option value="">Select warehouse...</option>
                            @foreach($this->warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }} ({{ $warehouse->branch?->name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Expected Delivery Date</label>
                        <input type="date" wire:model="expected_delivery_date"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Requested By</label>
                        <input type="text" wire:model="requested_by"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30"
                            placeholder="Requester name or department">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Payment Terms</label>
                        <select wire:model="payment_terms"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            <option value="">Select terms</option>
                            <option value="cod">COD</option>
                            <option value="7_days">7 days</option>
                            <option value="14_days">14 days</option>
                            <option value="30_days">30 days</option>
                            <option value="45_days">45 days</option>
                            <option value="60_days">60 days</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Delivery Instructions</label>
                        <textarea wire:model="delivery_instructions" rows="2"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30"
                            placeholder="Gate, contact person, unload time window, etc."></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Internal Notes</label>
                        <textarea wire:model="notes" rows="2"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30"
                            placeholder="Additional notes for your purchasing team..."></textarea>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-white/10">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Order Items</h3>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-white/50">
                            @if($supplier_id)
                                Add products from this supplier's catalog
                            @else
                                Select a supplier first to add products
                            @endif
                        </p>
                    </div>
                    <button wire:click="openProductPicker" @disabled(!$supplier_id)
                        class="inline-flex h-9 items-center gap-2 rounded-lg bg-slate-900 px-3 text-sm font-medium text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        <span>Add Product</span>
                    </button>
                </div>

                @error('items') 
                    <div class="border-b border-rose-200 bg-rose-50 px-6 py-3 dark:border-rose-500/20 dark:bg-rose-500/10">
                        <p class="text-sm text-rose-600 dark:text-rose-400">{{ $message }}</p>
                    </div>
                @enderror

                @if(count($items) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60">Product</th>
                                    <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-center w-32">Qty</th>
                                    <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-right w-40">Unit Price</th>
                                    <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-right w-40">Subtotal</th>
                                    <th class="px-6 py-3 w-12"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                                @foreach($items as $index => $item)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                                        <td class="px-6 py-3">
                                            <p class="font-medium text-slate-900 dark:text-white">{{ $item['product_name'] }}</p>
                                            <p class="text-xs text-slate-500 dark:text-white/50">{{ $item['product_sku'] }}</p>
                                        </td>
                                        <td class="px-6 py-3">
                                            <input type="number" wire:model.live="items.{{ $index }}.quantity" min="1"
                                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm text-center dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                                        </td>
                                        <td class="px-6 py-3">
                                            <input type="number" wire:model.live="items.{{ $index }}.unit_price" min="0" step="100"
                                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm text-right dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                                        </td>
                                        <td class="px-6 py-3 text-right font-medium text-slate-900 dark:text-white">
                                            Rp {{ number_format(($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0), 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-3 text-right">
                                            <button wire:click="removeItem({{ $index }})"
                                                class="text-slate-400 transition hover:text-rose-500">
                                                @svg('heroicon-o-trash', 'h-4 w-4')
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        @svg('heroicon-o-shopping-cart', 'h-12 w-12 text-slate-300 dark:text-white/20')
                        <p class="mt-4 text-sm text-slate-500 dark:text-white/50">No items added yet</p>
                        @if($supplier_id)
                            <button wire:click="openProductPicker"
                                class="mt-3 text-sm font-medium text-slate-900 hover:underline dark:text-white">
                                Add your first product
                            </button>
                        @else
                            <p class="mt-2 text-xs text-slate-400 dark:text-white/40">Select a supplier above to start adding products</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Summary --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-4">
                {{-- Selected Supplier Info --}}
                @if($supplier_id)
                    @php $selectedSupplier = $this->suppliers->firstWhere('id', $supplier_id); @endphp
                    @if($selectedSupplier)
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 dark:bg-white/10">
                                    @svg('heroicon-o-building-storefront', 'h-5 w-5 text-slate-600 dark:text-white/60')
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-slate-900 dark:text-white truncate">{{ $selectedSupplier->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-white/50">{{ $selectedSupplier->code }}</p>
                                    @if($selectedSupplier->contact_name)
                                        <p class="mt-1 text-xs text-slate-500 dark:text-white/50">Contact: {{ $selectedSupplier->contact_name }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Order Summary --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
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
                        <div class="flex justify-between text-sm">
                            <dt class="text-slate-500 dark:text-white/60">Subtotal</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</dd>
                        </div>
                        @if($this->taxAmount > 0)
                            <div class="flex justify-between text-sm">
                                <dt class="text-slate-500 dark:text-white/60">Tax</dt>
                                <dd class="font-medium text-slate-900 dark:text-white">Rp {{ number_format($this->taxAmount, 0, ',', '.') }}</dd>
                            </div>
                        @endif
                        <div class="border-t border-slate-200 pt-4 dark:border-white/10">
                            <div class="flex justify-between">
                                <dt class="text-base font-medium text-slate-900 dark:text-white">Total</dt>
                                <dd class="text-lg font-bold text-slate-900 dark:text-white">
                                    Rp {{ number_format($this->total, 0, ',', '.') }}
                                </dd>
                            </div>
                        </div>
                    </dl>

                    <button wire:click="save" @disabled(count($items) === 0)
                        class="mt-6 w-full inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                        @svg('heroicon-o-check', 'h-4 w-4')
                        <span>Create Purchase Order</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Product Picker Modal --}}
    @if($showProductPicker)
        <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/50 pt-20" wire:click.self="$set('showProductPicker', false)">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl dark:bg-slate-900">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-white/10">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Add Product</h3>
                        <p class="mt-0.5 text-sm text-slate-500 dark:text-white/50">Select from supplier's product catalog</p>
                    </div>
                    <button wire:click="$set('showProductPicker', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">
                        @svg('heroicon-o-x-mark', 'h-5 w-5')
                    </button>
                </div>

                <div class="border-b border-slate-200 px-6 py-3 dark:border-white/10">
                    <input type="text" wire:model.live.debounce.300ms="productSearch" placeholder="Search products..."
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white"
                        autofocus>
                </div>

                <div class="max-h-80 overflow-y-auto">
                    @forelse($this->supplierProducts as $product)
                        <button wire:click="addProduct({{ $product->id }})"
                            class="flex w-full items-center gap-4 border-b border-slate-100 px-6 py-3 text-left transition hover:bg-slate-50 dark:border-white/5 dark:hover:bg-white/5">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-900 dark:text-white">{{ $product->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-white/50">
                                    {{ $product->sku }}
                                    @if($product->pivot->supplier_sku)
                                        · Supplier SKU: {{ $product->pivot->supplier_sku }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                @if($product->pivot->supplier_price)
                                    <p class="font-medium text-slate-900 dark:text-white">Rp {{ number_format($product->pivot->supplier_price, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-slate-400">No price set</p>
                                @endif
                                @if($product->pivot->min_order_qty > 1)
                                    <p class="text-xs text-slate-500 dark:text-white/50">Min: {{ $product->pivot->min_order_qty }}</p>
                                @endif
                            </div>
                            @svg('heroicon-o-plus-circle', 'h-5 w-5 text-slate-400 shrink-0')
                        </button>
                    @empty
                        <div class="px-6 py-12 text-center">
                            @svg('heroicon-o-cube', 'mx-auto h-10 w-10 text-slate-300 dark:text-white/20')
                            <p class="mt-3 text-sm text-slate-500 dark:text-white/50">
                                @if($productSearch)
                                    No products found matching "{{ $productSearch }}"
                                @else
                                    No more products available from this supplier
                                @endif
                            </p>
                            <p class="mt-1 text-xs text-slate-400 dark:text-white/40">
                                Add products to this supplier from the supplier edit page
                            </p>
                        </div>
                    @endforelse
                </div>

                <div class="border-t border-slate-200 px-6 py-4 dark:border-white/10">
                    <button wire:click="$set('showProductPicker', false)"
                        class="w-full inline-flex h-10 items-center justify-center rounded-xl border border-slate-300 bg-white text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
