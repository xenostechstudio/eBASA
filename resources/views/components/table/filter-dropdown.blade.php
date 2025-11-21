@props([
    'label' => 'Status',
    'options' => [],
    'selected' => null,
    'onSelect' => null,
])

<div class="relative" x-data="{ open: false }">
    <button
        type="button"
        @click="open = !open"
        class="mt-1 inline-flex items-center justify-center text-white/70 transition hover:text-white"
        aria-label="{{ $label }} filter"
    >
        @svg('heroicon-m-square-3-stack-3d', 'h-7 w-7')
    </button>
    <div
        x-cloak
        x-show="open"
        @click.away="open = false"
        class="absolute right-0 z-20 mt-2 w-48 rounded-2xl border border-white/10 bg-slate-900/95 p-2 text-sm text-white shadow-xl"
    >
        <p class="px-3 text-[11px] uppercase tracking-[0.35em] text-white/40">{{ strtoupper($label) }}</p>
        @foreach ($options as $key => $optionLabel)
            <button
                type="button"
                @click.prevent="open = false"
                @if ($onSelect)
                    wire:click="{{ $onSelect }}('{{ $key }}')"
                @endif
                class="mt-1 flex w-full items-center justify-between rounded-xl px-3 py-2 text-left {{ $selected === $key ? 'bg-white text-slate-900 font-semibold' : 'text-white/80 hover:bg-white/10' }}"
            >
                {{ $optionLabel }}
                @if ($selected === $key)
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M5 10l3 3 7-7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                @endif
            </button>
        @endforeach
    </div>
</div>
