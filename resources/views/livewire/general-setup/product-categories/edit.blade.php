<div>
    {{-- Flash Message --}}
    @if (session()->has('flash'))
        @php $flash = session('flash'); @endphp
        <x-alert :type="$flash['type'] ?? 'info'" :title="$flash['title'] ?? null">
            {{ $flash['message'] ?? '' }}
        </x-alert>
    @endif

    <div class="space-y-6">
        {{-- Header --}}
        <x-form.section-header
            title="Category Information"
            description="Update category details and manage associated products."
        />

        <div class="grid gap-6 lg:grid-cols-3">
        {{-- Form Card --}}
        <div class="lg:col-span-1">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
                <div class="space-y-5 px-6 py-5">
                    {{-- Category Details --}}
                    <div class="space-y-3">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
                            Category Details
                        </p>

                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                wire:model="name"
                                autocomplete="off"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                                placeholder="e.g. Beverages, Snacks"
                            >
                            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Sort Order --}}
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                Sort Order
                            </label>
                            <input
                                type="number"
                                id="sort_order"
                                wire:model="sort_order"
                                min="0"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                                placeholder="0"
                            >
                            <p class="mt-1 text-[11px] text-slate-400 dark:text-white/40">
                                Lower numbers appear first.
                            </p>
                            @error('sort_order') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Color --}}
                        <div>
                            <label for="color" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                Category Color
                            </label>
                            <div class="mt-1 flex items-center gap-2">
                                <input
                                    type="color"
                                    id="color"
                                    wire:model="color"
                                    class="h-10 w-12 cursor-pointer rounded-lg border border-slate-200 bg-slate-50 p-1 dark:border-white/10 dark:bg-white/5"
                                >
                                <input
                                    type="text"
                                    wire:model="color"
                                    class="block w-24 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                                    placeholder="#6366f1"
                                >
                            </div>
                            @error('color') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                                Description
                            </label>
                            <textarea
                                id="description"
                                wire:model="description"
                                rows="2"
                                class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                                placeholder="Brief description"
                            ></textarea>
                            @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Status --}}
                        <label class="flex items-center gap-3 cursor-pointer pt-2">
                            <input
                                type="checkbox"
                                wire:model="is_active"
                                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-0 dark:border-white/20 dark:bg-white/10"
                            >
                            <span class="text-sm font-medium text-slate-700 dark:text-white/80">Active</span>
                        </label>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10">
                    @if ($editingCategory)
                        <div class="mb-2 text-[11px] text-slate-400 dark:text-white/40">
                            <p>
                                Created {{ optional($editingCategory->created_at)->format(config('basa.datetime_format')) }}
                                by {{ optional($editingCategory->createdBy)->name ?? 'System' }}
                            </p>
                            @if ($editingCategory->updated_at)
                                <p>
                                    Last updated {{ optional($editingCategory->updated_at)->format(config('basa.datetime_format')) }}
                                    by {{ optional($editingCategory->updatedBy)->name ?? 'System' }}
                                </p>
                            @endif
                        </div>
                    @endif

                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            wire:click="cancel"
                            class="inline-flex h-10 flex-1 items-center justify-center rounded-xl border border-slate-200 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-white/80 dark:hover:bg-white/5"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            wire:click="save"
                            wire:loading.attr="disabled"
                            wire:target="save"
                            class="inline-flex h-10 flex-1 items-center justify-center gap-2 rounded-xl bg-slate-900 text-sm font-medium text-white transition hover:bg-slate-800 disabled:opacity-50 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                        >
                            <span wire:loading.remove wire:target="save">
                                @svg('heroicon-o-check', 'h-4 w-4')
                            </span>
                            <span wire:loading wire:target="save">
                                @svg('heroicon-o-arrow-path', 'h-4 w-4 animate-spin')
                            </span>
                            <span>Save</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Products Relation Manager --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-white/10">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Products in this Category</h3>
                        <p class="text-xs text-slate-500 dark:text-white/50">
                            {{ $this->productStats['total'] }} products · {{ $this->productStats['active'] }} active
                        </p>
                    </div>
                    <a
                        href="{{ route('general-setup.products.create') }}"
                        class="inline-flex h-8 items-center gap-1.5 rounded-lg bg-slate-900 px-3 text-xs font-medium text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                    >
                        @svg('heroicon-o-plus', 'h-3.5 w-3.5')
                        <span>Add Product</span>
                    </a>
                </div>

                {{-- Search & Filter --}}
                <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-3 dark:border-white/10 sm:flex-row sm:items-center">
                    <div class="relative flex-1">
                        @svg('heroicon-o-magnifying-glass', 'absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="productSearch"
                            placeholder="Search products..."
                            class="block w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                        >
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            wire:click="setProductStatusFilter('')"
                            class="inline-flex h-8 items-center rounded-lg px-3 text-xs font-medium transition {{ $productStatusFilter === '' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'text-slate-600 hover:bg-slate-100 dark:text-white/70 dark:hover:bg-white/10' }}"
                        >
                            All
                        </button>
                        <button
                            type="button"
                            wire:click="setProductStatusFilter('active')"
                            class="inline-flex h-8 items-center rounded-lg px-3 text-xs font-medium transition {{ $productStatusFilter === 'active' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'text-slate-600 hover:bg-slate-100 dark:text-white/70 dark:hover:bg-white/10' }}"
                        >
                            Active
                        </button>
                        <button
                            type="button"
                            wire:click="setProductStatusFilter('inactive')"
                            class="inline-flex h-8 items-center rounded-lg px-3 text-xs font-medium transition {{ $productStatusFilter === 'inactive' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'text-slate-600 hover:bg-slate-100 dark:text-white/70 dark:hover:bg-white/10' }}"
                        >
                            Inactive
                        </button>
                    </div>
                </div>

                {{-- Products Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-slate-200 bg-slate-50/50 dark:border-white/10 dark:bg-white/5">
                            <tr>
                                <th class="whitespace-nowrap px-6 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-white/50">SKU</th>
                                <th class="whitespace-nowrap px-6 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-white/50">Product</th>
                                <th class="whitespace-nowrap px-6 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-white/50">Price</th>
                                <th class="whitespace-nowrap px-6 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-white/50">Stock</th>
                                <th class="whitespace-nowrap px-6 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-white/50">Status</th>
                                <th class="whitespace-nowrap px-6 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-white/50"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/10">
                            @forelse ($this->products as $product)
                                <tr class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                                    <td class="whitespace-nowrap px-6 py-3">
                                        <span class="font-mono text-xs text-slate-600 dark:text-white/70">{{ $product->sku }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3">
                                        <a
                                            href="{{ route('general-setup.products.edit', $product) }}"
                                            class="font-medium text-slate-900 hover:text-slate-700 dark:text-white dark:hover:text-white/80"
                                        >
                                            {{ $product->name }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3 text-slate-600 dark:text-white/70">
                                        Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3 text-slate-600 dark:text-white/70">
                                        {{ $product->stock_quantity }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3">
                                        @if ($product->is_active)
                                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600 dark:bg-white/10 dark:text-white/60">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3 text-right">
                                        <button
                                            type="button"
                                            wire:click="removeProduct({{ $product->id }})"
                                            wire:confirm="Remove this product from the category?"
                                            class="text-xs text-slate-400 hover:text-red-500 dark:text-white/40 dark:hover:text-red-400"
                                        >
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            @svg('heroicon-o-cube', 'h-8 w-8 text-slate-300 dark:text-white/20')
                                            <p class="text-sm text-slate-500 dark:text-white/50">No products in this category</p>
                                            <a
                                                href="{{ route('general-setup.products.create') }}"
                                                class="text-sm font-medium text-slate-900 hover:text-slate-700 dark:text-white dark:hover:text-white/80"
                                            >
                                                Add your first product →
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($this->products->hasPages())
                    <div class="border-t border-slate-200 px-6 py-3 dark:border-white/10">
                        {{ $this->products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
