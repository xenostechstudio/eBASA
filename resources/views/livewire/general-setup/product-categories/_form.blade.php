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
    </div>
</div>

<div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4 dark:border-white/10">
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
