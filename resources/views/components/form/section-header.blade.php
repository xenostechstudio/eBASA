@props([
    'title',
    'description' => null,
])

<div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div class="flex flex-col gap-1">
        <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
            {{ $title }}
        </h2>

        @if ($description)
            <p class="text-sm text-slate-500 dark:text-white/60">
                {{ $description }}
            </p>
        @endif
    </div>

    @if (isset($actions))
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
