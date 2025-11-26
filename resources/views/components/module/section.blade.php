@props([
    'as' => 'section',
    'padding' => 'lg', // none, sm, md, lg
    'rounded' => '4xl', // none, xl, 3xl, 4xl
    'gap' => 'lg', // none, sm, md, lg
    'border' => true,
    'borderClass' => null,
    'backgroundClass' => null,
    'shadow' => false,
])

@php
    $paddingClasses = [
        'none' => 'p-0',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];

    $roundedClasses = [
        'none' => null,
        'xl' => 'rounded-2xl',
        '3xl' => 'rounded-[28px]',
        '4xl' => 'rounded-[32px]',
    ];

    $gapClasses = [
        'none' => null,
        'sm' => 'space-y-4',
        'md' => 'space-y-6',
        'lg' => 'space-y-8',
    ];

    $classes = collect([
        $roundedClasses[$rounded] ?? $roundedClasses['4xl'],
        $paddingClasses[$padding] ?? $paddingClasses['lg'],
        $gapClasses[$gap] ?? $gapClasses['none'],
        $border ? ($borderClass ?? 'border border-white/10') : null,
        $backgroundClass ?? 'bg-white/5',
        $shadow ? 'shadow-[0_25px_60px_rgba(15,23,42,0.35)]' : null,
    ])->filter()->implode(' ');
@endphp

<{{ $as }} {{ $attributes->class($classes) }}>
    {{ $slot }}
</{{ $as }}>
