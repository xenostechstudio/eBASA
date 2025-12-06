<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-white/50">
                <a href="{{ route('inventory.catalog.products') }}" class="hover:text-slate-700 dark:hover:text-white" wire:navigate>Products</a>
                <span>/</span>
                <span class="text-slate-900 dark:text-white">Add Product</span>
            </nav>
            <h1 class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">Add Product to Branch</h1>
            @if ($activeBranch)
                <p class="mt-1 text-sm text-slate-500 dark:text-white/60">Adding product to {{ $activeBranch->name }}</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('inventory.catalog.products') }}" wire:navigate
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                Cancel
            </a>
            <button wire:click="save" type="button"
                class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                @svg('heroicon-o-check', 'h-4 w-4')
                <span>Add Product</span>
            </button>
        </div>
    </div>

    @if (!$activeBranch)
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/30 dark:bg-amber-500/10">
            <div class="flex items-center gap-3">
                @svg('heroicon-o-exclamation-triangle', 'h-5 w-5 text-amber-600 dark:text-amber-400')
                <p class="text-sm text-amber-800 dark:text-amber-200">Please select a branch first to add products.</p>
            </div>
        </div>
    @else
        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Main Form --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Product Selection --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Select Product</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Choose a product from the master catalog to add to this branch.</p>

                    <div class="mt-6">
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Product <span class="text-rose-500">*</span></label>
                        
                        <div x-data="{
                            open: false,
                            search: '',
                            selected: null,
                            products: @js($availableProducts),
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
                                $wire.set('product_id', product.id);
                                $wire.set('selling_price', product.selling_price);
                                $wire.set('cost_price', product.cost_price);
                            }
                        }" class="relative mt-2">
                            <button @click="open = !open" type="button"
                                class="flex w-full items-center justify-between rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-left text-sm dark:border-white/10 dark:bg-slate-950/40">
                                <span x-text="selected ? selected.name + ' (' + selected.sku + ')' : 'Select a product...'" 
                                    :class="selected ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-white/40'"></span>
                                @svg('heroicon-o-chevron-down', 'h-4 w-4 text-slate-400')
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute left-0 top-full z-50 mt-1 w-full rounded-xl border border-slate-200 bg-white shadow-lg dark:border-white/10 dark:bg-slate-900">
                                <div class="p-3">
                                    <div class="relative">
                                        @svg('heroicon-o-magnifying-glass', 'absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                                        <input type="text" x-model="search" placeholder="Search products by name or SKU..."
                                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm focus:border-slate-400 focus:outline-none dark:border-white/10 dark:bg-white/5 dark:text-white">
                                    </div>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <template x-for="product in filtered" :key="product.id">
                                        <button @click="selectProduct(product)" type="button"
                                            class="flex w-full items-center justify-between px-4 py-3 text-left hover:bg-slate-50 dark:hover:bg-white/5">
                                            <div>
                                                <p class="font-medium text-slate-900 dark:text-white" x-text="product.name"></p>
                                                <p class="text-xs text-slate-500 dark:text-white/50" x-text="product.sku"></p>
                                            </div>
                                            <span class="text-sm font-medium text-slate-600 dark:text-white/70" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(product.selling_price)"></span>
                                        </button>
                                    </template>
                                    <div x-show="filtered.length === 0" class="px-4 py-6 text-center text-sm text-slate-500">
                                        No products found
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('product_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror

                        @if ($availableProducts->isEmpty())
                            <p class="mt-2 text-sm text-slate-500 dark:text-white/50">All products have been added to this branch.</p>
                        @endif
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Branch Pricing</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Override the default pricing for this branch.</p>

                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Selling Price</label>
                            <input type="number" min="0" step="100" wire:model="selling_price" placeholder="Use default"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30">
                            <p class="mt-1 text-xs text-slate-400">Leave empty to use master price</p>
                            @error('selling_price') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Cost Price</label>
                            <input type="number" min="0" step="100" wire:model="cost_price" placeholder="Use default"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30">
                            <p class="mt-1 text-xs text-slate-400">Leave empty to use master price</p>
                            @error('cost_price') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Stock --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Stock Levels</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Set the initial stock and thresholds for this branch.</p>

                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Stock Quantity <span class="text-rose-500">*</span></label>
                            <input type="number" min="0" wire:model="stock_quantity"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            @error('stock_quantity') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Min Stock Level <span class="text-rose-500">*</span></label>
                            <input type="number" min="0" wire:model="min_stock_level"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white">
                            @error('min_stock_level') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Max Stock Level</label>
                            <input type="number" min="0" wire:model="max_stock_level" placeholder="No limit"
                                class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30">
                            @error('max_stock_level') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    {{-- Status --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Status</h3>

                        <div class="mt-4 space-y-4">
                            <label class="flex items-center gap-3">
                                <input type="checkbox" wire:model="is_available"
                                    class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10">
                                <span class="text-sm text-slate-700 dark:text-white/80">Available for sale</span>
                            </label>
                            <p class="ml-7 text-xs text-slate-400">Product will be visible in POS</p>

                            <label class="flex items-center gap-3 border-t border-slate-200 pt-4 dark:border-white/10">
                                <input type="checkbox" wire:model="is_featured"
                                    class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500 dark:border-white/30 dark:bg-white/10">
                                <span class="text-sm text-slate-700 dark:text-white/80">Featured product</span>
                            </label>
                            <p class="ml-7 text-xs text-slate-400">Show in featured section</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
