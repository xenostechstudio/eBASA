@props([
    'label' => null,
    'model',
    'placeholder' => 'Select option',
    'colSpan' => 6,
    'required' => false,
])

@php
    $span = max(1, min(12, (int) $colSpan));
    $spanClasses = [
        1 => 'md:col-span-1',
        2 => 'md:col-span-2',
        3 => 'md:col-span-3',
        4 => 'md:col-span-4',
        5 => 'md:col-span-5',
        6 => 'md:col-span-6',
        7 => 'md:col-span-7',
        8 => 'md:col-span-8',
        9 => 'md:col-span-9',
        10 => 'md:col-span-10',
        11 => 'md:col-span-11',
        12 => 'md:col-span-12',
    ];

    $columnClass = trim('col-span-12 '.($spanClasses[$span] ?? 'md:col-span-12'));
    $selectClasses = 'mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-0 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:focus:ring-2 dark:focus:ring-white/40';
@endphp

<div {{ $attributes->merge(['class' => $columnClass]) }}>
    @if ($label)
        <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 dark:text-white/50">
            {{ $label }}
            @if ($required)
                <span class="text-rose-500">*</span>
            @endif
        </label>
    @endif

    <select wire:model.defer="{{ $model }}" class="{{ $selectClasses }}" @if ($required) required @endif>
        <option value="" class="bg-white text-slate-400 dark:bg-slate-900 dark:text-white/70">{{ $placeholder }}</option>
        {{ $slot }}
    </select>

    @error($model)
        <p class="mt-1 text-xs text-rose-500 dark:text-rose-300">{{ $message }}</p>
    @enderror
</div>
