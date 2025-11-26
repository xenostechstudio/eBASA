@props([
    'placeholder' => 'Search',
    'icon' => 'heroicon-o-magnifying-glass',
])

<div class="relative">
    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400 dark:text-white/40" aria-hidden="true">
        @svg($icon, 'h-5 w-5')
    </span>
    <input
        type="text"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'h-11 w-64 rounded-2xl border border-slate-200 bg-white pl-11 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white/80 dark:placeholder:text-white/40 dark:focus:border-white/40',
        ]) }}
    />
</div>
