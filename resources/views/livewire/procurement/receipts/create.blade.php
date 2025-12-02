<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Procurement</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">Receive Goods</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-white/60">Record goods received against a purchase order.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('procurement.receipts') }}"
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                Cancel
            </a>
            <button wire:click="save" @disabled(count($items) === 0)
                class="inline-flex h-10 items-center gap-2 rounded-xl bg-emerald-600 px-4 text-sm font-medium text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">
                @svg('heroicon-o-check', 'h-4 w-4')
                <span>Complete Receipt</span>
            </button>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Receipt Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Receipt Details</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Select the purchase order and enter receipt information.</p>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Purchase Order <span class="text-rose-500">*</span></label>
                        <select wire:model.live="purchase_order_id"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            <option value="">Select purchase order...</option>
                            @foreach($this->receivablePurchaseOrders as $po)
                                <option value="{{ $po->id }}">
                                    {{ $po->reference }} - {{ $po->supplier->name }}
                                    ({{ ucfirst(str_replace('_', ' ', $po->status)) }})
                                </option>
                            @endforeach
                        </select>
                        @error('purchase_order_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Receive to Warehouse <span class="text-rose-500">*</span></label>
                        <select wire:model="warehouse_id"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            <option value="">Select warehouse...</option>
                            @foreach($this->warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }} ({{ $warehouse->branch?->name }})</option>
                            @endforeach
                        </select>
                        @error('warehouse_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Received Date <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="received_date"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        @error('received_date') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Received By</label>
                        <input type="text" wire:model="received_by_name" placeholder="Name of person receiving goods"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30">
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Delivery Note / Invoice #</label>
                        <input type="text" wire:model="delivery_note_number" placeholder="Supplier's delivery note number"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Notes</label>
                        <textarea wire:model="notes" rows="2" placeholder="Any notes about this receipt..."
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30"></textarea>
                    </div>
                </div>
            </div>

            {{-- Items to Receive --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Items to Receive</h3>
                    <p class="mt-0.5 text-sm text-slate-500 dark:text-white/50">
                        @if($purchase_order_id)
                            Enter quantities received for each item. You can do partial receipts.
                        @else
                            Select a purchase order to see items.
                        @endif
                    </p>
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
                                    <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-center">Ordered</th>
                                    <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-center">Already Received</th>
                                    <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-center">Remaining</th>
                                    <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-center w-28">Receive Now</th>
                                    <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-center w-28">Accepted</th>
                                    <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-center w-28">Rejected</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                                @foreach($items as $index => $item)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                                        <td class="px-6 py-3">
                                            <p class="font-medium text-slate-900 dark:text-white">{{ $item['product_name'] }}</p>
                                            <p class="text-xs text-slate-500 dark:text-white/50">{{ $item['product_sku'] }}</p>
                                        </td>
                                        <td class="px-6 py-3 text-center text-slate-600 dark:text-white/70">
                                            {{ $item['ordered_qty'] }}
                                        </td>
                                        <td class="px-6 py-3 text-center text-slate-600 dark:text-white/70">
                                            {{ $item['received_qty'] }}
                                        </td>
                                        <td class="px-6 py-3 text-center">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $item['remaining_qty'] > 0 ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' }}">
                                                {{ $item['remaining_qty'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3">
                                            <input type="number" wire:model.live="items.{{ $index }}.quantity_to_receive"
                                                min="0" max="{{ $item['remaining_qty'] }}"
                                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm text-center dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                                        </td>
                                        <td class="px-6 py-3">
                                            <input type="number" wire:model.live="items.{{ $index }}.quantity_accepted"
                                                min="0" max="{{ $item['quantity_to_receive'] }}"
                                                class="w-full rounded-lg border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-sm text-center dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-white">
                                        </td>
                                        <td class="px-6 py-3">
                                            <input type="number" wire:model="items.{{ $index }}.quantity_rejected"
                                                min="0" readonly
                                                class="w-full rounded-lg border border-rose-300 bg-rose-50 px-3 py-1.5 text-sm text-center dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-white">
                                        </td>
                                    </tr>
                                    @if($item['quantity_rejected'] > 0)
                                        <tr class="bg-rose-50 dark:bg-rose-500/5">
                                            <td colspan="7" class="px-6 py-2">
                                                <div class="flex items-center gap-2">
                                                    <label class="text-xs font-medium text-rose-600 dark:text-rose-400">Rejection reason:</label>
                                                    <input type="text" wire:model="items.{{ $index }}.rejection_reason"
                                                        placeholder="Why were items rejected?"
                                                        class="flex-1 rounded-lg border border-rose-200 bg-white px-3 py-1 text-xs dark:border-rose-500/30 dark:bg-slate-950/40 dark:text-white">
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        @svg('heroicon-o-inbox-arrow-down', 'h-12 w-12 text-slate-300 dark:text-white/20')
                        <p class="mt-4 text-sm text-slate-500 dark:text-white/50">
                            @if($purchase_order_id)
                                All items from this PO have been fully received.
                            @else
                                Select a purchase order to see items to receive.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Summary --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-4">
                {{-- PO Info --}}
                @if($this->selectedPurchaseOrder)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 dark:bg-sky-500/20">
                                @svg('heroicon-o-document-text', 'h-5 w-5 text-sky-600 dark:text-sky-400')
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-900 dark:text-white">{{ $this->selectedPurchaseOrder->reference }}</p>
                                <p class="text-xs text-slate-500 dark:text-white/50">{{ $this->selectedPurchaseOrder->supplier->name }}</p>
                                <p class="mt-1 text-xs text-slate-500 dark:text-white/50">
                                    Ordered: {{ $this->selectedPurchaseOrder->order_date->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Receipt Summary --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Receipt Summary</h3>

                    <dl class="mt-6 space-y-4">
                        <div class="flex justify-between text-sm">
                            <dt class="text-slate-500 dark:text-white/60">Items</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">{{ count($items) }}</dd>
                        </div>
                        <div class="flex justify-between text-sm">
                            <dt class="text-slate-500 dark:text-white/60">Total to Receive</dt>
                            <dd class="font-medium text-slate-900 dark:text-white">{{ $this->totalToReceive }}</dd>
                        </div>
                        <div class="flex justify-between text-sm">
                            <dt class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                @svg('heroicon-o-check-circle', 'h-4 w-4')
                                Accepted
                            </dt>
                            <dd class="font-medium text-emerald-600 dark:text-emerald-400">{{ $this->totalAccepted }}</dd>
                        </div>
                        @if($this->totalRejected > 0)
                            <div class="flex justify-between text-sm">
                                <dt class="flex items-center gap-1 text-rose-600 dark:text-rose-400">
                                    @svg('heroicon-o-x-circle', 'h-4 w-4')
                                    Rejected
                                </dt>
                                <dd class="font-medium text-rose-600 dark:text-rose-400">{{ $this->totalRejected }}</dd>
                            </div>
                        @endif
                    </dl>

                    <div class="mt-6 rounded-xl bg-sky-50 p-4 dark:bg-sky-500/10">
                        <p class="text-xs text-sky-700 dark:text-sky-300">
                            <strong>Note:</strong> Completing this receipt will update the PO status and add accepted quantities to inventory.
                        </p>
                    </div>

                    <button wire:click="save" @disabled(count($items) === 0 || $this->totalToReceive === 0)
                        class="mt-6 w-full inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 text-sm font-medium text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">
                        @svg('heroicon-o-check', 'h-4 w-4')
                        <span>Complete Receipt</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
