@props([
    'tagline' => null,
    'title',
    'description' => null,
    'actions' => null,
])

<header {{ $attributes->merge(['class' => 'flex flex-wrap items-center justify-between gap-4']) }}>
    <div class="space-y-2 max-w-3xl">
        @if ($tagline)
            <p class="text-xs uppercase tracking-[0.5em] text-white/40">{{ $tagline }}</p>
        @endif

        <div>
            <h1 class="text-3xl font-semibold text-white">{{ $title }}</h1>
            @if ($description)
                <p class="text-sm text-white/60">{{ $description }}</p>
            @endif
        </div>
    </div>

    @if ($actions)
        <div class="flex flex-wrap gap-3">
            {{ $actions }}
        </div>
    @endif
</header>
