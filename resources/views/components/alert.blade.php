@props([
    'type' => 'info',
    'title' => null,
    'position' => 'top-right', // top-right (default), top-center
])

@php
    $styles = [
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-100',
        'error' => 'border-red-200 bg-red-50 text-red-800 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-100',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100',
        'info' => 'border-sky-200 bg-sky-50 text-sky-800 dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-100',
    ];

    $icons = [
        'success' => 'heroicon-o-check-circle',
        'error' => 'heroicon-o-x-circle',
        'warning' => 'heroicon-o-exclamation-triangle',
        'info' => 'heroicon-o-information-circle',
    ];

    $classes = $styles[$type] ?? $styles['info'];
    $icon = $icons[$type] ?? $icons['info'];

    $positionClasses = [
        // Default: top-right corner
        'top-right' => 'top-4 right-4 sm:right-6 justify-end',

        // Centered at top, with horizontal padding only when centering
        'top-center' => 'top-4 inset-x-0 justify-center px-4 sm:px-6',
    ][$position] ?? 'top-4 right-4 sm:right-6 justify-end';

    // Fixed overlay with very high z-index so it never sits behind other UI
    $wrapperClasses = 'pointer-events-none fixed z-[9999] flex ' . $positionClasses;
@endphp

<div
    x-data="{ visible: false }"
    x-init="setTimeout(() => { visible = true; setTimeout(() => visible = false, 7000) }, 50)"
    x-bind:class="visible ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-8'"
    class="{{ $wrapperClasses }} transform transition-all duration-300"
    hidden
    role="status"
    aria-live="polite"
>
    <div class="pointer-events-auto flex w-full max-w-2xl items-start gap-3 rounded-2xl border px-4 py-3 text-sm shadow-lg backdrop-blur-sm {{ $classes }}">
        <div class="mt-0.5 flex-shrink-0">
            @svg($icon, 'h-5 w-5')
        </div>

        <div class="flex-1">
            @if ($title)
                <h3 class="text-sm font-semibold leading-snug">
                    {{ $title }}
                </h3>
            @endif

            <div class="mt-0.5 leading-relaxed">
                {{ $slot }}
            </div>
        </div>

        <button
            type="button"
            class="ml-2 inline-flex h-6 w-6 items-center justify-center rounded-full text-xs font-medium transition hover:bg-black/5 dark:hover:bg-white/10"
            x-on:click="visible = false"
            aria-label="Dismiss notification"
        >
            @svg('heroicon-o-x-mark', 'h-4 w-4')
        </button>
    </div>
</div>
