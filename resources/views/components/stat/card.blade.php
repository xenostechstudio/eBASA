@props([
    'label',
    'value',
    'description' => null,
    'icon' => null,
    'iconColor' => 'text-slate-400', // text-emerald-500, text-sky-500, text-amber-500, text-red-500, etc.
    'tone' => 'neutral', // kept for backward compatibility (ignored in new design)
])

<div {{ $attributes->class('rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5') }}>
    <div class="flex items-center gap-3">
        {{-- Support both: prop-based icon (new) and slot-based icon (legacy) --}}
        @if ($icon instanceof \Illuminate\View\ComponentSlot)
            {{-- Legacy: slot-based icon --}}
            {{ $icon }}
        @elseif ($icon && is_string($icon))
            {{-- New: prop-based icon name --}}
            <x-dynamic-component :component="$icon" class="h-5 w-5 {{ $iconColor }}" />
        @endif
        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">
            {{ $label }}
        </p>
    </div>

    <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">
        {{ $value }}
    </p>

    @if ($description)
        <p class="mt-1 text-xs text-slate-500 dark:text-white/60">
            {{ $description }}
        </p>
    @endif
</div>
