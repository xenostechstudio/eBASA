@props([
    'placeholder' => 'Search',
    'icon' => 'heroicon-o-magnifying-glass',
])

<div class="relative">
    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-white/40" aria-hidden="true">
        @svg($icon, 'h-5 w-5')
    </span>
    <input
        type="text"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'h-11 w-64 rounded-2xl border border-white/10 bg-slate-950/40 pl-11 pr-4 text-sm text-white/80 placeholder:text-white/40 focus:border-white/40 focus:outline-none focus:ring-0',
        ]) }}
    />
</div>
