@php /** @var bool $isEditing */ @endphp

<div class="space-y-5 px-6 py-5 overflow-y-auto">
    <div class="space-y-3">
        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
            Category Information
        </p>
        <div class="space-y-4">
            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Category Name
                </label>
                <input
                    type="text"
                    id="name"
                    wire:model="name"
                    autocomplete="off"
                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                    placeholder="e.g. Beverages, Snacks, Electronics"
                >
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                <p class="mt-1 text-[11px] text-slate-400 dark:text-white/40">
                    A unique name to identify this category.
                </p>
            </div>

            {{-- Color --}}
            <div>
                <label for="color" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                    Color
                    <span class="font-normal text-slate-400 dark:text-white/40">(optional)</span>
                </label>
                <div class="mt-1 flex items-center gap-3">
                    <input
                        type="color"
                        id="color"
                        wire:model="color"
                        class="h-9 w-12 cursor-pointer rounded-lg border border-slate-200 bg-white p-1 dark:border-white/10 dark:bg-white/5"
                    >
                    <input
                        type="text"
                        wire:model="color"
                        class="block w-28 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-mono text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                        placeholder="#f59e0b"
                    >
                </div>
                @error('color') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                <p class="mt-1 text-[11px] text-slate-400 dark:text-white/40">
                    Used to highlight this category in product lists.
                </p>
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
                    rows="3"
                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                    placeholder="Brief description of this category"
                ></textarea>
                @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="space-y-3 border-t border-dashed border-slate-200 pt-4 dark:border-white/10">
        <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-white/40">
            Display Settings
        </p>
        <div>
            <label for="sort_order" class="block text-sm font-medium text-slate-700 dark:text-white/80">
                Sort Order
            </label>
            <input
                type="number"
                id="sort_order"
                wire:model="sort_order"
                min="0"
                class="mt-1 block w-32 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/40"
                placeholder="0"
            >
            @error('sort_order') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            <p class="mt-1 text-[11px] text-slate-400 dark:text-white/40">
                Lower numbers appear first. Categories with the same order are sorted alphabetically.
            </p>
        </div>
        <div class="mt-3">
            <label class="flex items-center gap-3 cursor-pointer">
                <input
                    type="checkbox"
                    wire:model="is_active"
                    class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-0 dark:border-white/20 dark:bg-white/10"
                >
                <span class="text-sm font-medium text-slate-700 dark:text-white/80">Category is active and can be used for products</span>
            </label>
        </div>
    </div>
</div>

<div class="flex flex-col gap-2 border-t border-slate-200 px-6 py-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
    @if ($isEditing && isset($editingCategory) && $editingCategory)
        <div class="text-[11px] text-slate-400 dark:text-white/40">
            <p>
                Created
                <span class="font-medium text-slate-500 dark:text-white/60">
                    {{ optional($editingCategory->created_at)->format(config('basa.datetime_format')) }}
                </span>
            </p>
            <p>
                Last updated
                <span class="font-medium text-slate-500 dark:text-white/60">
                    {{ optional($editingCategory->updated_at)->format(config('basa.datetime_format')) }}
                </span>
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
            <span>{{ $isEditing ? 'Save Changes' : 'Create Category' }}</span>
        </button>
    </div>
</div>
