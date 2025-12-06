<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-white/50">
                <a href="{{ route('inventory.catalog.price-lists') }}" class="hover:text-slate-700 dark:hover:text-white" wire:navigate>Price Lists</a>
                <span>/</span>
                <span class="text-slate-900 dark:text-white">Create</span>
            </nav>
            <h1 class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">Create Price List</h1>
            @if ($activeBranch)
                <p class="mt-1 text-sm text-slate-500 dark:text-white/60">Creating price list for {{ $activeBranch->name }}</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('inventory.catalog.price-lists') }}" wire:navigate
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                Cancel
            </a>
            <button wire:click="save" type="button"
                class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                @svg('heroicon-o-check', 'h-4 w-4')
                <span>Create Price List</span>
            </button>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Main Form --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Basic Info --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Price List Information</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Basic details about this price list.</p>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Code</label>
                        <input type="text" wire:model="code"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        @error('code') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Name <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="name" placeholder="Price list name"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30">
                        @error('name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Type <span class="text-rose-500">*</span></label>
                        <select wire:model="type"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            @foreach ($types as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Priority</label>
                        <input type="number" min="0" wire:model="priority"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                        <p class="mt-1 text-xs text-slate-400">Higher priority takes precedence</p>
                        @error('priority') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Description</label>
                        <textarea wire:model="description" rows="2" placeholder="Price list description..."
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30"></textarea>
                        @error('description') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Price List Items --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Products & Prices</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Define prices for products in this list.</p>
                    </div>
                    <button wire:click="addPriceListItem" type="button"
                        class="inline-flex h-9 items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                        @svg('heroicon-o-plus', 'h-4 w-4')
                        <span>Add Product</span>
                    </button>
                </div>

                @error('priceListItems') <p class="mt-4 text-xs text-rose-500">{{ $message }}</p> @enderror

                {{-- Items Table --}}
                @if (count($priceListItems) > 0)
                    <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 dark:border-white/10">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50/50 dark:border-white/10 dark:bg-white/5">
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">Product</th>
                                    <th class="w-32 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">Price</th>
                                    <th class="w-28 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">Discount %</th>
                                    <th class="w-24 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/50">Min Qty</th>
                                    <th class="w-12 px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @foreach ($priceListItems as $index => $item)
                                    <tr class="group">
                                        <td class="px-4 py-3">
                                            <div x-data="{
                                                open: false,
                                                search: '',
                                                selected: @js($products->firstWhere('id', $item['product_id'])),
                                                products: @js($products),
                                                get filtered() {
                                                    if (!this.search) return this.products;
                                                    return this.products.filter(p => 
                                                        p.name.toLowerCase().includes(this.search.toLowerCase()) ||
                                                        p.sku.toLowerCase().includes(this.search.toLowerCase())
                                                    );
                                                },
                                                selectProduct(product) {
                                                    this.selected = product;
                                                    this.open = false;
                                                    this.search = '';
                                                    $wire.set('priceListItems.{{ $index }}.product_id', product.id);
                                                    $wire.set('priceListItems.{{ $index }}.price', product.selling_price);
                                                }
                                            }" class="relative">
                                                <button @click="open = !open" type="button"
                                                    class="flex w-full items-center justify-between rounded-lg border border-slate-300 bg-white px-3 py-2 text-left text-sm dark:border-white/10 dark:bg-slate-950/40">
                                                    <span x-text="selected ? selected.name : 'Select product...'" 
                                                        :class="selected ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-white/40'"></span>
                                                    @svg('heroicon-o-chevron-down', 'h-4 w-4 text-slate-400')
                                                </button>
                                                
                                                <div x-show="open" @click.away="open = false" x-transition
                                                    class="absolute left-0 top-full z-50 mt-1 w-80 rounded-xl border border-slate-200 bg-white shadow-lg dark:border-white/10 dark:bg-slate-900">
                                                    <div class="p-2">
                                                        <div class="relative">
                                                            @svg('heroicon-o-magnifying-glass', 'absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                                                            <input type="text" x-model="search" placeholder="Search products..."
                                                                class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm focus:border-slate-400 focus:outline-none dark:border-white/10 dark:bg-white/5 dark:text-white">
                                                        </div>
                                                    </div>
                                                    <div class="max-h-48 overflow-y-auto">
                                                        <template x-for="product in filtered" :key="product.id">
                                                            <button @click="selectProduct(product)" type="button"
                                                                class="flex w-full items-center justify-between px-3 py-2 text-left text-sm hover:bg-slate-50 dark:hover:bg-white/5">
                                                                <div>
                                                                    <p class="font-medium text-slate-900 dark:text-white" x-text="product.name"></p>
                                                                    <p class="text-xs text-slate-500 dark:text-white/50" x-text="product.sku"></p>
                                                                </div>
                                                                <span class="text-xs text-slate-500" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(product.selling_price)"></span>
                                                            </button>
                                                        </template>
                                                        <div x-show="filtered.length === 0" class="px-3 py-4 text-center text-sm text-slate-500">
                                                            No products found
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('priceListItems.'.$index.'.product_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" min="0" step="100" wire:model="priceListItems.{{ $index }}.price"
                                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" min="0" max="100" step="1" wire:model="priceListItems.{{ $index }}.discount_percent"
                                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-center dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" min="1" wire:model="priceListItems.{{ $index }}.min_qty"
                                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-center dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                                        </td>
                                        <td class="px-4 py-3">
                                            <button wire:click="removePriceListItem({{ $index }})" type="button"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 opacity-0 transition group-hover:opacity-100 hover:bg-rose-100 hover:text-rose-600 dark:hover:bg-rose-500/20 dark:hover:text-rose-400">
                                                @svg('heroicon-o-trash', 'h-4 w-4')
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="mt-6 flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 py-12 text-center dark:border-white/10">
                        @svg('heroicon-o-currency-dollar', 'h-10 w-10 text-slate-300 dark:text-white/20')
                        <p class="mt-3 text-sm text-slate-500 dark:text-white/50">No products added yet</p>
                        <button wire:click="addPriceListItem" type="button"
                            class="mt-3 text-sm font-medium text-slate-900 hover:underline dark:text-white">
                            Add your first product
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">
                {{-- Validity --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Validity Period</h3>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Valid From</label>
                            <input type="date" wire:model="valid_from"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            @error('valid_from') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Valid Until</label>
                            <input type="date" wire:model="valid_until"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            <p class="mt-1 text-xs text-slate-400">Leave empty for no expiry</p>
                            @error('valid_until') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Status</h3>

                    <div class="mt-4 space-y-4">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" wire:model="is_active"
                                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10">
                            <span class="text-sm text-slate-700 dark:text-white/80">Active</span>
                        </label>
                        <p class="ml-7 text-xs text-slate-400">Price list will be available for use</p>

                        <label class="flex items-center gap-3 border-t border-slate-200 pt-4 dark:border-white/10">
                            <input type="checkbox" wire:model="is_default"
                                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10">
                            <span class="text-sm text-slate-700 dark:text-white/80">Set as default</span>
                        </label>
                        <p class="ml-7 text-xs text-slate-400">This will be the primary price list for this branch</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
