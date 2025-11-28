@props([
    'label',
    'value',
    'description' => null,
    'tone' => 'neutral', // neutral, success, warning, danger, info
])

@php
    $tones = [
        'neutral' => [
            'wrapper' => 'rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-white/5',
            'label' => 'text-slate-400 dark:text-white/40',
        ],
        'success' => [
            'wrapper' => 'rounded-2xl border border-emerald-200 bg-emerald-50/50 p-6 dark:border-emerald-500/20 dark:bg-emerald-500/10',
            'label' => 'text-emerald-600 dark:text-emerald-400',
        ],
        'warning' => [
            'wrapper' => 'rounded-2xl border border-amber-200 bg-amber-50/50 p-6 dark:border-amber-500/20 dark:bg-amber-500/10',
            'label' => 'text-amber-600 dark:text-amber-400',
        ],
        'danger' => [
            'wrapper' => 'rounded-2xl border border-rose-200 bg-rose-50/50 p-6 dark:border-rose-500/20 dark:bg-rose-500/10',
            'label' => 'text-rose-600 dark:text-rose-400',
        ],
        'info' => [
            'wrapper' => 'rounded-2xl border border-sky-200 bg-sky-50/50 p-6 dark:border-sky-500/20 dark:bg-sky-500/10',
            'label' => 'text-sky-600 dark:text-sky-300',
        ],
    ];

    $styles = $tones[$tone] ?? $tones['neutral'];
@endphp

<div {{ $attributes->class($styles['wrapper']) }}>
    <div class="flex items-center gap-3">
        @isset($icon)
            {{ $icon }}
        @endisset
        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] {{ $styles['label'] }}">
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
