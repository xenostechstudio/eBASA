<div>
    @if (session()->has('status'))
        <x-alert type="success">
            {{ session('status') }}
        </x-alert>
    @endif

    <div class="space-y-6" x-data="{ activeTab: 'details' }">
    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-white/50">Procurement</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">{{ $supplier->name }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-white/60">
                <span class="font-mono">{{ $supplier->code }}</span>
                @if($supplier->is_active)
                    <span class="ml-2 inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">Active</span>
                @else
                    <span class="ml-2 inline-flex items-center rounded-full bg-rose-100 px-2 py-0.5 text-xs font-medium text-rose-700 dark:bg-rose-500/20 dark:text-rose-400">Inactive</span>
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('procurement.suppliers') }}"
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                @svg('heroicon-o-arrow-left', 'h-4 w-4')
                <span>Back</span>
            </a>
            <button wire:click="save"
                class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                @svg('heroicon-o-check', 'h-4 w-4')
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat.card label="Lifetime Spend" :value="'Rp ' . number_format($this->stats['lifetimeSpend'] / 1000000, 1) . 'M'" description="All time" tone="success">
            <x-slot:icon>@svg('heroicon-o-banknotes', 'h-5 w-5 text-emerald-500')</x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Total Orders" :value="number_format($this->stats['ordersCount'])" description="Purchase orders" tone="neutral">
            <x-slot:icon>@svg('heroicon-o-document-text', 'h-5 w-5 text-slate-500')</x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Open Orders" :value="number_format($this->stats['openOrders'])" description="Awaiting receipt" tone="warning">
            <x-slot:icon>@svg('heroicon-o-clock', 'h-5 w-5 text-amber-500')</x-slot:icon>
        </x-stat.card>

        <x-stat.card label="Products" :value="number_format($this->stats['productsCount'])" description="Items supplied" tone="info">
            <x-slot:icon>@svg('heroicon-o-cube', 'h-5 w-5 text-sky-500')</x-slot:icon>
        </x-stat.card>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-slate-200 dark:border-white/10">
        <nav class="-mb-px flex gap-6">
            <button @click="activeTab = 'details'" :class="activeTab === 'details' ? 'border-slate-900 text-slate-900 dark:border-white dark:text-white' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white'"
                class="border-b-2 pb-3 text-sm font-medium transition">
                Supplier Details
            </button>
            <button @click="activeTab = 'products'" :class="activeTab === 'products' ? 'border-slate-900 text-slate-900 dark:border-white dark:text-white' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white'"
                class="border-b-2 pb-3 text-sm font-medium transition">
                Products ({{ $this->stats['productsCount'] }})
            </button>
            <button @click="activeTab = 'orders'" :class="activeTab === 'orders' ? 'border-slate-900 text-slate-900 dark:border-white dark:text-white' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-white/50 dark:hover:text-white'"
                class="border-b-2 pb-3 text-sm font-medium transition">
                Purchase Orders ({{ $this->stats['ordersCount'] }})
            </button>
        </nav>
    </div>

    {{-- Tab: Supplier Details --}}
    <div x-show="activeTab === 'details'" x-cloak>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Supplier Information</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Core details for this supplier.</p>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Name</label>
                    <input type="text" wire:model="form.name"
                        class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    @error('form.name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Code</label>
                    <input type="text" wire:model="form.code"
                        class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                    @error('form.code') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
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
                    @error('form.email') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Phone</label>
                    <input type="text" wire:model="form.phone"
                        class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Tax Number (NPWP)</label>
                    <input type="text" wire:model="form.tax_number"
                        class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Payment Terms (days)</label>
                    <input type="number" min="0" wire:model="form.payment_terms"
                        class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                </div>

                <div class="flex items-center gap-3 pt-6">
                    <button type="button" wire:click="$set('form.is_active', !{{ $form['is_active'] ? 'true' : 'false' }})"
                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $form['is_active'] ? 'bg-emerald-500' : 'bg-slate-200 dark:bg-slate-700' }}">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $form['is_active'] ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                    <span class="text-sm text-slate-700 dark:text-white/70">Active Supplier</span>
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

    {{-- Tab: Products (Relation Manager) --}}
    <div x-show="activeTab === 'products'" x-cloak>
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-white/10">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Products Supplied</h3>
                    <p class="mt-0.5 text-sm text-slate-500 dark:text-white/50">Products this supplier can provide with pricing info.</p>
                </div>
                <button wire:click="openAddProductModal"
                    class="inline-flex h-9 items-center gap-2 rounded-lg bg-slate-900 px-3 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                    @svg('heroicon-o-plus', 'h-4 w-4')
                    <span>Add Product</span>
                </button>
            </div>

            {{-- Search --}}
            <div class="border-b border-slate-200 px-6 py-3 dark:border-white/10">
                <input type="text" wire:model.live.debounce.300ms="productsSearch" placeholder="Search products..."
                    class="w-full max-w-xs rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5">
                        <tr>
                            <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60">Product</th>
                            <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60">Supplier SKU</th>
                            <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-right">Supplier Price</th>
                            <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-center">Lead Time</th>
                            <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-center">Min Qty</th>
                            <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-center">Preferred</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse ($this->supplierProducts as $product)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="px-6 py-3">
                                    <p class="font-medium text-slate-900 dark:text-white">{{ $product->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-white/50">{{ $product->sku }}</p>
                                </td>
                                <td class="px-6 py-3 text-slate-600 dark:text-white/70">
                                    {{ $product->pivot->supplier_sku ?? '-' }}
                                </td>
                                <td class="px-6 py-3 text-right font-medium text-slate-900 dark:text-white">
                                    @if($product->pivot->supplier_price)
                                        Rp {{ number_format($product->pivot->supplier_price, 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center text-slate-600 dark:text-white/70">
                                    {{ $product->pivot->lead_time_days ? $product->pivot->lead_time_days . ' days' : '-' }}
                                </td>
                                <td class="px-6 py-3 text-center text-slate-600 dark:text-white/70">
                                    {{ $product->pivot->min_order_qty }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <button wire:click="togglePreferred({{ $product->id }})" class="group">
                                        @if($product->pivot->is_preferred)
                                            @svg('heroicon-s-star', 'h-5 w-5 text-amber-400 group-hover:text-amber-500')
                                        @else
                                            @svg('heroicon-o-star', 'h-5 w-5 text-slate-300 group-hover:text-amber-400 dark:text-white/20')
                                        @endif
                                    </button>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <button wire:click="removeProduct({{ $product->id }})" wire:confirm="Remove this product from supplier?"
                                        class="text-slate-400 transition hover:text-rose-500">
                                        @svg('heroicon-o-trash', 'h-4 w-4')
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        @svg('heroicon-o-cube', 'h-10 w-10 text-slate-300 dark:text-white/20')
                                        <p class="mt-3 text-sm text-slate-500 dark:text-white/50">No products assigned to this supplier yet.</p>
                                        <button wire:click="openAddProductModal" class="mt-3 text-sm font-medium text-slate-900 hover:underline dark:text-white">
                                            Add your first product
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($this->supplierProducts->hasPages())
                <div class="border-t border-slate-200 px-6 py-4 dark:border-white/10">
                    {{ $this->supplierProducts->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Tab: Purchase Orders (Relation Manager) --}}
    <div x-show="activeTab === 'orders'" x-cloak>
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-white/10">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Purchase Orders</h3>
                    <p class="mt-0.5 text-sm text-slate-500 dark:text-white/50">All purchase orders for this supplier.</p>
                </div>
                <button wire:click="createOrderForSupplier"
                    class="inline-flex h-9 items-center gap-2 rounded-lg bg-slate-900 px-3 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                    @svg('heroicon-o-plus', 'h-4 w-4')
                    <span>New Order</span>
                </button>
            </div>

            {{-- Filters --}}
            <div class="flex flex-wrap items-center gap-3 border-b border-slate-200 px-6 py-3 dark:border-white/10">
                <input type="text" wire:model.live.debounce.300ms="ordersSearch" placeholder="Search reference..."
                    class="w-full max-w-xs rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">

                <div class="flex items-center gap-1">
                    @foreach(['all' => 'All', 'draft' => 'Draft', 'approved' => 'Approved', 'partially_received' => 'Partial', 'received' => 'Received'] as $value => $label)
                        <button wire:click="setOrdersStatusFilter('{{ $value }}')"
                            class="rounded-lg px-3 py-1.5 text-xs font-medium transition {{ $ordersStatusFilter === $value ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'text-slate-600 hover:bg-slate-100 dark:text-white/60 dark:hover:bg-white/10' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5">
                        <tr>
                            <th class="px-6 py-3">
                                <button wire:click="setOrdersSort('reference')" class="flex items-center gap-1 font-medium text-slate-600 dark:text-white/60">
                                    Reference
                                    @if($ordersSortField === 'reference')
                                        @svg($ordersSortDirection === 'asc' ? 'heroicon-o-chevron-up' : 'heroicon-o-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3">
                                <button wire:click="setOrdersSort('order_date')" class="flex items-center gap-1 font-medium text-slate-600 dark:text-white/60">
                                    Date
                                    @if($ordersSortField === 'order_date')
                                        @svg($ordersSortDirection === 'asc' ? 'heroicon-o-chevron-up' : 'heroicon-o-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60">Warehouse</th>
                            <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60">Status</th>
                            <th class="px-6 py-3 font-medium text-slate-600 dark:text-white/60 text-right">
                                <button wire:click="setOrdersSort('total')" class="flex items-center gap-1 ml-auto">
                                    Total
                                    @if($ordersSortField === 'total')
                                        @svg($ordersSortDirection === 'asc' ? 'heroicon-o-chevron-up' : 'heroicon-o-chevron-down', 'h-3 w-3')
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse ($this->orders as $order)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="px-6 py-3">
                                    <a href="{{ route('procurement.orders.edit', $order) }}" class="font-medium text-slate-900 hover:underline dark:text-white">
                                        {{ $order->reference }}
                                    </a>
                                </td>
                                <td class="px-6 py-3 text-slate-600 dark:text-white/70">
                                    {{ $order->order_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-3 text-slate-600 dark:text-white/70">
                                    {{ $order->warehouse?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                        {{ match($order->status) {
                                            'draft' => 'bg-slate-100 text-slate-600 dark:bg-slate-500/20 dark:text-slate-400',
                                            'pending_approval' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                                            'approved' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400',
                                            'partially_received' => 'bg-violet-100 text-violet-700 dark:bg-violet-500/20 dark:text-violet-400',
                                            'received' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                            'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
                                            default => 'bg-slate-100 text-slate-600'
                                        } }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right font-medium text-slate-900 dark:text-white">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('procurement.orders.edit', $order) }}" class="text-slate-400 transition hover:text-slate-600 dark:hover:text-white">
                                        @svg('heroicon-o-pencil-square', 'h-4 w-4')
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        @svg('heroicon-o-document-text', 'h-10 w-10 text-slate-300 dark:text-white/20')
                                        <p class="mt-3 text-sm text-slate-500 dark:text-white/50">No purchase orders yet.</p>
                                        <button wire:click="createOrderForSupplier" class="mt-3 text-sm font-medium text-slate-900 hover:underline dark:text-white">
                                            Create your first order
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($this->orders->hasPages())
                <div class="border-t border-slate-200 px-6 py-4 dark:border-white/10">
                    {{ $this->orders->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Add Product Modal --}}
    @if($showAddProductModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="$set('showAddProductModal', false)">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-slate-900">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Add Product to Supplier</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Select a product and set supplier-specific details.</p>

                <div class="mt-6 space-y-4">
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Product</label>
                        <select wire:model="selectedProductId"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            <option value="">Select product...</option>
                            @foreach($this->availableProducts as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                            @endforeach
                        </select>
                        @error('selectedProductId') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Supplier Price</label>
                            <input type="number" wire:model="supplierPrice" min="0" step="100"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Supplier SKU</label>
                            <input type="text" wire:model="supplierSku"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Lead Time (days)</label>
                            <input type="number" wire:model="leadTimeDays" min="0"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Min Order Qty</label>
                            <input type="number" wire:model="minOrderQty" min="1"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="$set('showAddProductModal', false)"
                        class="inline-flex h-10 items-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                        Cancel
                    </button>
                    <button wire:click="addProduct"
                        class="inline-flex h-10 items-center rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                        Add Product
                    </button>
                </div>
            </div>
        </div>
    @endif
    </div>
</div>
