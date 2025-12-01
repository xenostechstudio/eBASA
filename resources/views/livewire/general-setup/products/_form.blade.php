@php /** @var bool $isEditing */ @endphp

<div class="space-y-5 px-6 py-5 overflow-y-auto max-h-[70vh]">
    {{-- Basic Information --}}
    <div class="space-y-3">
        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
            Basic Information
        </p>

        <div class="flex flex-col md:flex-row gap-4 items-start">
            {{-- Image column --}}
            <div class="shrink-0 space-y-2" x-data>
                <label for="product_image" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Product Image
                    <span class="font-normal text-slate-400 dark:text-white/40">(optional)</span>
                </label>
                <button
                    type="button"
                    class="relative block text-slate-400 transition hover:opacity-80 dark:text-white/40"
                    @click="$refs.productImageInput.click()"
                >
                    <div class="aspect-[3/4] w-[240px] overflow-hidden rounded-2xl border border-dashed border-slate-300 bg-slate-50/80 shadow-sm dark:border-white/20 dark:bg-white/10">
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="Product image preview" class="h-full w-full object-cover">
                        @elseif ($this->image_path)
                            <img src="{{ Storage::url($this->image_path) }}" alt="Product image" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full flex-col items-center justify-center gap-1 px-3 text-center">
                                @svg('heroicon-o-photo', 'h-8 w-8')
                                <p class="text-[11px] font-medium text-slate-500 dark:text-white/70">Click to add image</p>
                                <p class="text-[10px] text-slate-400 dark:text-white/60">PNG or JPG up to 2MB</p>
                            </div>
                        @endif
                    </div>
                </button>

                <div class="space-y-1">
                    <input
                        x-ref="productImageInput"
                        id="product_image"
                        type="file"
                        class="hidden"
                        wire:model="image"
                        accept="image/*"
                    >
                    @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Text fields column --}}
            <div class="flex-1 min-w-0 space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <x-form.input
                        label="SKU"
                        model="sku"
                        placeholder="e.g. PRD-001"
                        col-span="1"
                        :required="true"
                    />

                    <x-form.input
                        label="Product Name"
                        model="name"
                        placeholder="e.g. Coca Cola 330ml"
                        col-span="1"
                        :required="true"
                    />
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <x-form.input
                        label="Brand (optional)"
                        model="brand"
                        placeholder="e.g. Coca Cola, Nike"
                        col-span="1"
                    />

                    <x-form.input
                        label="Size (optional)"
                        model="size"
                        placeholder="e.g. 330ml, L, XL"
                        col-span="1"
                    />

                    <x-form.input
                        label="Color (optional)"
                        model="color"
                        placeholder="e.g. Red, Black"
                        col-span="1"
                    />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <x-form.input
                        label="Barcode (optional)"
                        model="barcode"
                        placeholder="e.g. 8991234567890"
                        col-span="1"
                    />

                    {{-- Category --}}
                    <div class="col-span-12 md:col-span-1">
                        <label for="category_id" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                            Category
                        </label>
                        <div class="mt-2">
                            <x-form.searchable-select
                                name="category_id"
                                id="category_id"
                                wire:model.live="category_id"
                                :options="$this->categories"
                                value-key="id"
                                label-key="name"
                                placeholder="Select category"
                            />
                        </div>
                        @error('category_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Description --}}
                <x-form.input
                    label="Description (optional)"
                    model="description"
                    placeholder="Brief product description"
                    :textarea="true"
                    rows="2"
                />
            </div>
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
                <div class="mt-1">
                    <x-form.searchable-select
                        name="unit"
                        id="unit"
                        wire:model.live="unit"
                        :options="[
                            ['id' => 'pcs', 'name' => 'Pieces (pcs)'],
                            ['id' => 'kg', 'name' => 'Kilogram (kg)'],
                            ['id' => 'g', 'name' => 'Gram (g)'],
                            ['id' => 'l', 'name' => 'Liter (l)'],
                            ['id' => 'ml', 'name' => 'Milliliter (ml)'],
                            ['id' => 'box', 'name' => 'Box'],
                            ['id' => 'pack', 'name' => 'Pack'],
                        ]"
                        value-key="id"
                        label-key="name"
                        placeholder="Select unit"
                    />
                </div>
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

    {{-- Internal Notes --}}
    <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
            Internal Notes
        </p>
        <div>
            <label for="internal_notes" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                Notes for internal use
                <span class="font-normal text-slate-400 dark:text-white/40">(optional, not shown to customers)</span>
            </label>
            <textarea
                id="internal_notes"
                wire:model="internal_notes"
                rows="3"
                class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                placeholder="Store any special handling instructions, supplier info, or internal notes here."
            ></textarea>
            @error('internal_notes') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
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
