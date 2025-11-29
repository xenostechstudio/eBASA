@props([
    'name' => '',
    'placeholder' => 'Select an option',
    'options' => [],
    'valueKey' => 'id',
    'labelKey' => 'name',
    'sublabelKey' => null,
    'disabled' => false,
])

@php
    $wireModel = $attributes->whereStartsWith('wire:model')->first();
@endphp

<div
    x-data="{
        open: false,
        search: '',
        selectedValue: @entangle($wireModel).live,
        selectedLabel: '',
        highlightedIndex: -1,
        options: @js(collect($options)->map(fn($opt) => [
            'value' => data_get($opt, $valueKey),
            'label' => data_get($opt, $labelKey),
            'sublabel' => $sublabelKey ? data_get($opt, $sublabelKey) : null,
        ])->values()->toArray()),

        get filteredOptions() {
            if (!this.search) return this.options;
            const q = this.search.toLowerCase();
            return this.options.filter(opt => 
                opt.label.toLowerCase().includes(q) || 
                (opt.sublabel && opt.sublabel.toLowerCase().includes(q))
            );
        },

        init() {
            this.syncLabel();
            this.$watch('selectedValue', () => this.syncLabel());
        },

        syncLabel() {
            const found = this.options.find(o => String(o.value) === String(this.selectedValue));
            this.selectedLabel = found ? found.label : '';
        },

        select(option) {
            this.selectedValue = option.value;
            this.selectedLabel = option.label;
            this.search = '';
            this.open = false;
            this.highlightedIndex = -1;
        },

        clear() {
            this.selectedValue = '';
            this.selectedLabel = '';
            this.search = '';
            this.highlightedIndex = -1;
        },

        onKeydown(e) {
            if (!this.open) {
                if (['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(e.key)) {
                    e.preventDefault();
                    this.open = true;
                }
                return;
            }

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    this.highlightedIndex = Math.min(this.highlightedIndex + 1, this.filteredOptions.length - 1);
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    this.highlightedIndex = Math.max(this.highlightedIndex - 1, 0);
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (this.highlightedIndex >= 0 && this.filteredOptions[this.highlightedIndex]) {
                        this.select(this.filteredOptions[this.highlightedIndex]);
                    }
                    break;
                case 'Escape':
                    e.preventDefault();
                    this.open = false;
                    this.search = '';
                    this.highlightedIndex = -1;
                    break;
            }
        }
    }"
    @click.outside="open = false; search = ''; highlightedIndex = -1"
    class="relative"
>
    {{-- Trigger / Display --}}
    <button
        type="button"
        @click="open = !open; if (open) $nextTick(() => $refs.searchInput.focus())"
        @keydown="onKeydown"
        :disabled="{{ $disabled ? 'true' : 'false' }}"
        class="flex w-full items-center justify-between rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-left text-sm text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-0 disabled:cursor-not-allowed disabled:opacity-50 dark:border-white/10 dark:bg-slate-950/40 dark:text-white"
    >
        <span x-text="selectedLabel || '{{ $placeholder }}'" :class="{ 'text-slate-400 dark:text-white/40': !selectedLabel }"></span>
        <span class="flex items-center gap-1">
            <template x-if="selectedValue">
                <button type="button" @click.stop="clear()" class="rounded p-0.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/10 dark:hover:text-white">
                    @svg('heroicon-o-x-mark', 'h-4 w-4')
                </button>
            </template>
            @svg('heroicon-o-chevron-down', 'h-4 w-4 text-slate-400 transition-transform', ['x-bind:class' => "{ 'rotate-180': open }"])
        </span>
    </button>

    {{-- Dropdown --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute left-0 z-50 mt-1 w-full rounded-xl border border-slate-200 bg-white shadow-lg dark:border-white/10 dark:bg-slate-900"
        style="display: none;"
    >
        {{-- Search input --}}
        <div class="border-b border-slate-100 p-2 dark:border-white/10">
            <div class="relative">
                @svg('heroicon-o-magnifying-glass', 'pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400')
                <input
                    x-ref="searchInput"
                    x-model="search"
                    @keydown="onKeydown"
                    type="text"
                    placeholder="Search..."
                    class="w-full rounded-lg border-0 bg-slate-50 py-2 pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:ring-0 dark:bg-slate-800 dark:text-white dark:placeholder:text-white/40"
                >
            </div>
        </div>

        {{-- Options list --}}
        <ul class="max-h-60 overflow-y-auto p-1">
            <template x-if="filteredOptions.length === 0">
                <li class="px-3 py-2 text-center text-sm text-slate-500 dark:text-white/50">No results found</li>
            </template>
            <template x-for="(option, index) in filteredOptions" :key="option.value">
                <li
                    @click="select(option)"
                    @mouseenter="highlightedIndex = index"
                    :class="{
                        'bg-slate-100 dark:bg-white/10': highlightedIndex === index,
                        'bg-slate-50 dark:bg-white/5': String(selectedValue) === String(option.value) && highlightedIndex !== index
                    }"
                    class="cursor-pointer rounded-lg px-3 py-2 text-sm text-slate-900 transition hover:bg-slate-100 dark:text-white dark:hover:bg-white/10"
                >
                    <span x-text="option.label"></span>
                    <template x-if="option.sublabel">
                        <span class="ml-1 text-xs text-slate-500 dark:text-white/50" x-text="'— ' + option.sublabel"></span>
                    </template>
                </li>
            </template>
        </ul>
    </div>

    {{-- Hidden input for form submission --}}
    <input type="hidden" name="{{ $name }}" :value="selectedValue">
</div>
