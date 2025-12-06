<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <nav class="flex items-center gap-2 text-sm text-slate-500 dark:text-white/50">
                <a href="{{ route('inventory.catalog.products') }}" class="hover:text-slate-700 dark:hover:text-white" wire:navigate>Products</a>
                <span>/</span>
                <span class="text-slate-900 dark:text-white">{{ $branchProduct->product->name ?? 'Edit' }}</span>
            </nav>
            <h1 class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">Edit Product Configuration</h1>
            @if ($activeBranch)
                <p class="mt-1 text-sm text-slate-500 dark:text-white/60">{{ $activeBranch->name }}</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="delete" wire:confirm="Are you sure you want to remove this product from this branch?" type="button"
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-rose-300 bg-white px-4 text-sm font-medium text-rose-600 transition hover:bg-rose-50 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500/20">
                @svg('heroicon-o-trash', 'h-4 w-4')
                <span>Remove</span>
            </button>
            <a href="{{ route('inventory.catalog.products') }}" wire:navigate
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                Cancel
            </a>
            <button wire:click="save" type="button"
                class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90">
                @svg('heroicon-o-check', 'h-4 w-4')
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Main Form --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Product Info (Read-only) --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Product Information</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Master product details (read-only).</p>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Product Name</label>
                        <p class="mt-2 text-sm font-medium text-slate-900 dark:text-white">{{ $branchProduct->product->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">SKU</label>
                        <p class="mt-2 text-sm font-medium text-slate-900 dark:text-white">{{ $branchProduct->product->sku ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Category</label>
                        <p class="mt-2 text-sm font-medium text-slate-900 dark:text-white">{{ $branchProduct->product->category->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">Master Price</label>
                        <p class="mt-2 text-sm font-medium text-slate-900 dark:text-white">Rp {{ number_format($branchProduct->product->selling_price ?? 0, 0, ',', '.') }}</p>
                    </div>
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
                <p class="mt-1 text-sm text-slate-500 dark:text-white/50">Manage stock and thresholds for this branch.</p>

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

                {{-- Stock Status --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Stock Status</h3>

                    <div class="mt-4">
                        @php
                            $stockStatus = 'in_stock';
                            if ($stock_quantity <= 0) {
                                $stockStatus = 'out_of_stock';
                            } elseif ($stock_quantity <= $min_stock_level) {
                                $stockStatus = 'low_stock';
                            }
                        @endphp

                        @if ($stockStatus === 'out_of_stock')
                            <div class="flex items-center gap-3 rounded-xl bg-rose-50 p-3 dark:bg-rose-500/10">
                                @svg('heroicon-o-x-circle', 'h-5 w-5 text-rose-600 dark:text-rose-400')
                                <div>
                                    <p class="text-sm font-medium text-rose-800 dark:text-rose-300">Out of Stock</p>
                                    <p class="text-xs text-rose-600 dark:text-rose-400">No items available</p>
                                </div>
                            </div>
                        @elseif ($stockStatus === 'low_stock')
                            <div class="flex items-center gap-3 rounded-xl bg-amber-50 p-3 dark:bg-amber-500/10">
                                @svg('heroicon-o-exclamation-triangle', 'h-5 w-5 text-amber-600 dark:text-amber-400')
                                <div>
                                    <p class="text-sm font-medium text-amber-800 dark:text-amber-300">Low Stock</p>
                                    <p class="text-xs text-amber-600 dark:text-amber-400">{{ $stock_quantity }} items remaining</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-3 rounded-xl bg-emerald-50 p-3 dark:bg-emerald-500/10">
                                @svg('heroicon-o-check-circle', 'h-5 w-5 text-emerald-600 dark:text-emerald-400')
                                <div>
                                    <p class="text-sm font-medium text-emerald-800 dark:text-emerald-300">In Stock</p>
                                    <p class="text-xs text-emerald-600 dark:text-emerald-400">{{ $stock_quantity }} items available</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
