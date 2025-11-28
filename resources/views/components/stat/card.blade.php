@props([
    'label',
    'value',
    'description' => null,
    'tone' => 'neutral', // neutral, success, warning, danger, info
])

@php
    $tones = [
        'neutral' => [
            'wrapper' => 'rounded-2xl border border-slate-300 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5',
            'label' => 'text-slate-500 dark:text-white/40',
            'icon' => 'bg-slate-100 ring-1 ring-slate-200 dark:bg-white/10 dark:ring-white/10',
        ],
        'success' => [
            'wrapper' => 'rounded-2xl border border-emerald-300 bg-white p-6 shadow-sm dark:border-emerald-500/20 dark:bg-emerald-500/10',
            'label' => 'text-emerald-600 dark:text-emerald-300',
            'icon' => 'bg-emerald-50 ring-1 ring-emerald-200 dark:bg-emerald-500/20 dark:ring-emerald-500/40',
        ],
        'warning' => [
            'wrapper' => 'rounded-2xl border border-amber-300 bg-white p-6 shadow-sm dark:border-amber-500/20 dark:bg-amber-500/10',
            'label' => 'text-amber-600 dark:text-amber-300',
            'icon' => 'bg-amber-50 ring-1 ring-amber-200 dark:bg-amber-500/20 dark:ring-amber-500/40',
        ],
        'danger' => [
            'wrapper' => 'rounded-2xl border border-rose-300 bg-white p-6 shadow-sm dark:border-rose-500/20 dark:bg-rose-500/10',
            'label' => 'text-rose-600 dark:text-rose-300',
            'icon' => 'bg-rose-50 ring-1 ring-rose-200 dark:bg-rose-500/20 dark:ring-rose-500/40',
        ],
        'info' => [
            'wrapper' => 'rounded-2xl border border-sky-300 bg-white p-6 shadow-sm dark:border-sky-500/20 dark:bg-sky-500/10',
            'label' => 'text-sky-600 dark:text-sky-300',
            'icon' => 'bg-sky-50 ring-1 ring-sky-200 dark:bg-sky-500/20 dark:ring-sky-500/40',
        ],
    ];

    $styles = $tones[$tone] ?? $tones['neutral'];
@endphp

<div {{ $attributes->class($styles['wrapper']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="space-y-2">
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] {{ $styles['label'] }}">
                {{ $label }}
            </p>

            <p class="text-3xl font-bold text-slate-900 dark:text-white">
                {{ $value }}
            </p>
        </div>

        @isset($icon)
            <div class="flex h-10 w-10 items-center justify-center rounded-2xl {{ $styles['icon'] ?? 'bg-slate-100 ring-1 ring-slate-200 dark:bg-white/10 dark:ring-white/10' }}">
                {{ $icon }}
            </div>
        @endisset
    </div>

    @if ($description)
        <p class="mt-3 text-xs text-slate-500 dark:text-white/60">
            {{ $description }}
        </p>
    @endif
</div>
