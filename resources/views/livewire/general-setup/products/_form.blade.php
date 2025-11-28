@php /** @var bool $isEditing */ @endphp

<div class="space-y-5 px-6 py-5 overflow-y-auto max-h-[70vh]">
    {{-- Basic Information --}}
    <div class="space-y-3">
        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
            Basic Information
        </p>
        <div class="grid gap-4 md:grid-cols-2">
            {{-- SKU --}}
            <div>
                <label for="sku" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    SKU <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="sku"
                    wire:model="sku"
                    autocomplete="off"
                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                    placeholder="e.g. PRD-001"
                >
                @error('sku') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Product Name <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    wire:model="name"
                    autocomplete="off"
                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg.white/5 dark:text-white dark:placeholder:text-white/40"
                    placeholder="e.g. Coca Cola 330ml"
                >
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Barcode --}}
            <div>
                <label for="barcode" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Barcode
                    <span class="font-normal text-slate-400 dark:text-white/40">(optional)</span>
                </label>
                <input
                    type="text"
                    id="barcode"
                    wire:model="barcode"
                    autocomplete="off"
                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                    placeholder="e.g. 8991234567890"
                >
                @error('barcode') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Category --}}
            <div>
                <label for="category_id" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Category
                </label>
                <select
                    id="category_id"
                    wire:model="category_id"
                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white"
                >
                    <option value="">Select category</option>
                    @foreach ($this->categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-2">
            <label for="product_image" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                Product Image
                <span class="font-normal text-slate-400 dark:text-white/40">(optional)</span>
            </label>
            <div class="mt-2 flex items-start gap-4">
                <div
                    class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-xl bg-slate-100 text-slate-400 dark:bg-white/10 dark:text-white/40"
                >
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" alt="Product image preview" class="h-full w-full object-cover">
                    @elseif ($this->image_path)
                        <img src="{{ Storage::url($this->image_path) }}" alt="Product image" class="h-full w-full object-cover">
                    @else
                        @svg('heroicon-o-photo', 'h-6 w-6')
                    @endif
                </div>

                <div class="space-y-1">
                    <label
                        for="product_image"
                        class="inline-flex cursor-pointer items-center rounded-xl bg-slate-900 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                    >
                        @svg('heroicon-o-arrow-up-tray', 'mr-1 h-4 w-4')
                        <span>Upload image</span>
                    </label>
                    <input
                        id="product_image"
                        type="file"
                        class="hidden"
                        wire:model="image"
                        accept="image/*"
                    >
                    <p class="text-[11px] text-slate-400 dark:text-white/40">
                        PNG or JPG up to 2MB.
                    </p>
                    @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                Description
                <span class="font-normal text-slate-400 dark:text-white/40">(optional)</span>
            </label>
            <textarea
                id="description"
                wire:model="description"
                rows="2"
                class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                placeholder="Brief product description"
            ></textarea>
            @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Pricing --}}
    <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
            Pricing
        </p>
        <div class="grid gap-4 md:grid-cols-3">
            {{-- Cost Price --}}
            <div>
                <label for="cost_price" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Cost Price <span class="text-red-500">*</span>
                </label>
                <div class="relative mt-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400">Rp</span>
                    <input
                        type="number"
                        id="cost_price"
                        wire:model="cost_price"
                        step="0.01"
                        min="0"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                        placeholder="0"
                    >
                </div>
                @error('cost_price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Selling Price --}}
            <div>
                <label for="selling_price" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Selling Price <span class="text-red-500">*</span>
                </label>
                <div class="relative mt-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400">Rp</span>
                    <input
                        type="number"
                        id="selling_price"
                        wire:model="selling_price"
                        step="0.01"
                        min="0"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                        placeholder="0"
                    >
                </div>
                @error('selling_price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Unit --}}
            <div>
                <label for="unit" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Unit <span class="text-red-500">*</span>
                </label>
                <select
                    id="unit"
                    wire:model="unit"
                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white"
                >
                    <option value="pcs">Pieces (pcs)</option>
                    <option value="kg">Kilogram (kg)</option>
                    <option value="g">Gram (g)</option>
                    <option value="l">Liter (l)</option>
                    <option value="ml">Milliliter (ml)</option>
                    <option value="box">Box</option>
                    <option value="pack">Pack</option>
                </select>
                @error('unit') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Inventory --}}
    <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text.white/40">
            Inventory
        </p>
        <div class="grid gap-4 md:grid-cols-3">
            {{-- Track Inventory Toggle --}}
            <div class="md:col-span-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        wire:model.live="track_inventory"
                        class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-0 dark:border-white/20 dark:bg-white/10"
                    >
                    <span class="text-sm font-medium text-slate-700 dark:text-white/80">Track inventory for this product</span>
                </label>
            </div>

            @if ($track_inventory)
                {{-- Stock Quantity --}}
                <div>
                    <label for="stock_quantity" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                        Current Stock
                    </label>
                    <input
                        type="number"
                        id="stock_quantity"
                        wire:model="stock_quantity"
                        min="0"
                        class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                        placeholder="0"
                    >
                    @error('stock_quantity') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Min Stock Level --}}
                <div>
                    <label for="min_stock_level" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                        Low Stock Alert
                    </label>
                    <input
                        type="number"
                        id="min_stock_level"
                        wire:model="min_stock_level"
                        min="0"
                        class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                        placeholder="0"
                    >
                    @error('min_stock_level') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    <p class="mt-1 text-[11px] text-slate-400 dark:text-white/40">
                        Alert when stock falls below this level.
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- Status --}}
    <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
            Status
        </p>
            <label class="flex items-center gap-3 cursor-pointer">
                <input
                    type="checkbox"
                    wire:model="is_active"
                    class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-0 dark:border-white/20 dark:bg-white/10"
                >
                <span class="text-sm font-medium text-slate-700 dark:text-white/80">Product is active and available for sale</span>
            </label>
    </div>
</div>

<div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
    @if ($isEditing && isset($editingProduct) && $editingProduct)
        <div class="text-[11px] text-slate-400 dark:text-white/40">
            <p>
                Created
                <span class="font-medium text-slate-500 dark:text-white/60">
                    {{ optional($editingProduct->created_at)->format(config('basa.datetime_format')) }}
                </span>
                @if ($editingProduct->createdBy)
                    by
                    <span class="font-medium text-slate-600 dark:text-white/80">
                        {{ $editingProduct->createdBy->name }}
                    </span>
                @endif
            </p>
            <p>
                Last updated
                <span class="font-medium text-slate-500 dark:text-white/60">
                    {{ optional($editingProduct->updated_at)->format(config('basa.datetime_format')) }}
                </span>
                @if ($editingProduct->updatedBy)
                    by
                    <span class="font-medium text-slate-600 dark:text-white/80">
                        {{ $editingProduct->updatedBy->name }}
                    </span>
                @endif
            </p>
        </div>
    @endif

    <div class="flex items-center justify-end gap-3 md:ml-auto">
        <button
            type="button"
            wire:click="closeModal"
            class="inline-flex h-10 items-center rounded-xl border border-slate-200 px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-white/80 dark:hover:bg-white/5"
        >
            Cancel
        </button>
        <button
            type="button"
            wire:click="save"
            wire:loading.attr="disabled"
            wire:target="save"
            class="inline-flex h-10 items-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-800 disabled:opacity-50 dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
        >
            <span wire:loading.remove wire:target="save">
                @svg('heroicon-o-check', 'h-4 w-4')
            </span>
            <span wire:loading wire:target="save">
                @svg('heroicon-o-arrow-path', 'h-4 w-4 animate-spin')
            </span>
            <span>{{ $isEditing ? 'Save Changes' : 'Create Product' }}</span>
        </button>
    </div>
</div>
