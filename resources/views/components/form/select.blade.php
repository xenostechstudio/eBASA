@props([
    'label' => null,
    'model',
    'placeholder' => 'Select option',
    'colSpan' => 6,
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
    $selectClasses = 'mt-2 w-full rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-white/40';
@endphp

<div {{ $attributes->merge(['class' => $columnClass]) }}>
    @if ($label)
        <label class="block text-xs uppercase tracking-[0.3em] text-white/50">{{ $label }}</label>
    @endif

    <select wire:model.defer="{{ $model }}" class="{{ $selectClasses }}">
        <option value="" class="bg-slate-900 text-white/70">{{ $placeholder }}</option>
        {{ $slot }}
    </select>

    @error($model)
        <p class="mt-1 text-xs text-rose-300">{{ $message }}</p>
    @enderror
</div>
