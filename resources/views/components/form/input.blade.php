@props([
    'label' => null,
    'model',
    'type' => 'text',
    'placeholder' => '',
    'colSpan' => 6,
    'textarea' => false,
    'rows' => 3,
    'required' => false,
    'disabled' => false,
    'helper' => null,
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
    $inputClasses = 'mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-0 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-500 disabled:border-slate-200 disabled:opacity-70 dark:border-white/10 dark:bg-slate-950/40 dark:text-white dark:placeholder:text-white/30 dark:focus:ring-2 dark:focus:ring-white/40 dark:disabled:bg-white/10 dark:disabled:text-white/40';
    $errorKey = str_replace(['\'', '"'], '', $model);
@endphp

<div {{ $attributes->merge(['class' => $columnClass]) }}>
    @if ($label)
        <label class="block text-sm font-medium text-slate-600 dark:text-white/50">
            {{ $label }}
            @if ($required)
                <span class="text-rose-500">*</span>
            @endif
        </label>
    @endif

    @if ($textarea)
        <textarea
            wire:model.defer="{{ $model }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            class="{{ $inputClasses }} rounded-2xl"
            @if ($required) required @endif
            @if ($disabled) disabled @endif
        ></textarea>
    @else
        <input
            type="{{ $type }}"
            wire:model.defer="{{ $model }}"
            placeholder="{{ $placeholder }}"
            class="{{ $inputClasses }}"
            @if ($required) required @endif
            @if ($disabled) disabled @endif
        />
    @endif

    @if ($helper)
        <p class="mt-1 text-[11px] text-slate-500 dark:text-white/50">{{ $helper }}</p>
    @endif

    @error($model)
        <p class="mt-1 text-xs text-rose-500 dark:text-rose-300">{{ $message }}</p>
    @enderror
</div>
