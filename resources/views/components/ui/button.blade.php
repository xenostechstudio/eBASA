@props([
    'variant' => 'primary',
    'size' => 'md',
    'as' => 'button',
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center rounded-full font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-white/60 gap-2';
    $sizeClasses = [
        'sm' => 'px-4 py-1.5 text-sm',
        'md' => 'px-5 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ][$size] ?? 'px-5 py-2 text-sm';

    $variantClasses = [
        'primary' => 'bg-white text-slate-900 hover:bg-white/90',
        'secondary' => 'border border-white/30 text-white/80 hover:text-white',
        'ghost' => 'text-white/70 hover:text-white',
    ][$variant] ?? 'bg-white text-slate-900';
@endphp

@if ($as === 'a')
    <a {{ $attributes->merge(['class' => implode(' ', [$baseClasses, $sizeClasses, $variantClasses])]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => implode(' ', [$baseClasses, $sizeClasses, $variantClasses])]) }}>
        {{ $slot }}
    </button>
@endif
