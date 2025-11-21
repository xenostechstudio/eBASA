@props([
    'title' => 'eBASA Portal',
    'showBrand' => true,
    'showHomeIcon' => true,
    'navLinks' => [],
])
@php
    $locales = collect(config('locale.available'));
    $currentLocale = $locales->firstWhere('code', app()->getLocale()) ?? $locales->first();
    $otherLocales = $locales->where('code', '!=', app()->getLocale());
@endphp
@php
    $resolvedNavLinks = ! empty($navLinks)
        ? $navLinks
        : [
            ['label' => 'Overview', 'href' => '#'],
            ['label' => 'Attendance', 'href' => '#'],
            ['label' => 'Payroll', 'href' => '#'],
            ['label' => 'Settings', 'href' => '#'],
        ];
@endphp

<header class="relative z-20 flex flex-wrap items-center justify-between gap-6 px-8 py-8 lg:flex-nowrap lg:px-12">
    <div class="flex flex-wrap items-center gap-4">
        @if ($showHomeIcon)
            <a href="{{ route('home') }}" class="text-white/70 transition hover:text-white" aria-label="Back to module list">
                @svg('healthicons-f-ui-menu-grid', 'h-6 w-6')
            </a>
        @endif
        <div class="flex flex-col">
            @if ($showBrand)
                <p class="text-xs uppercase tracking-[0.6em] text-white/40">BASA</p>
            @endif
            <h1 class="text-2xl font-semibold tracking-wide">{{ $title }}</h1>
        </div>

        <nav class="flex flex-wrap items-center gap-2 text-sm text-white/70 lg:ml-6">
            @foreach ($resolvedNavLinks as $link)
                @if (! empty($link['children']))
                    <div class="relative" data-dropdown>
                        <button type="button" data-dropdown-trigger class="flex items-center gap-1 rounded-full px-3 py-1 transition {{ ($link['active'] ?? false) ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}">
                            <span>{{ $link['label'] ?? '' }}</span>
                            <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.084l3.71-3.854a.75.75 0 011.08 1.04l-4.24 4.4a.75.75 0 01-1.08 0l-4.24-4.4a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div data-dropdown-panel class="absolute left-0 z-50 mt-3 hidden min-w-[15rem] rounded-2xl border border-white/10 bg-slate-900/95 p-3 text-white shadow-2xl">
                            <div class="space-y-2">
                                @foreach ($link['children'] as $child)
                                    <a href="{{ $child['href'] ?? '#' }}"
                                        class="block rounded-xl px-3 py-2 text-left transition hover:bg-white/10">
                                        <span class="text-sm font-medium text-white">{{ $child['label'] ?? '' }}</span>
                                        @if (! empty($child['description']))
                                            <p class="text-xs text-white/60">{{ $child['description'] }}</p>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ $link['href'] ?? '#' }}"
                        class="rounded-full px-3 py-1 transition {{ ($link['active'] ?? false) ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}">
                        {{ $link['label'] ?? '' }}
                    </a>
                @endif
            @endforeach
        </nav>
    </div>

    <div class="flex items-center gap-4">
        <div class="relative" data-dropdown="locale">
            <button type="button" data-dropdown-trigger class="flex items-center gap-2 rounded-full bg-white/10 px-3 py-2 text-xl">
                <span aria-label="Current locale">{{ $currentLocale['flag'] ?? '🌐' }}</span>
                <svg class="h-3.5 w-3.5 text-white/70" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.084l3.71-3.854a.75.75 0 011.08 1.04l-4.24 4.4a.75.75 0 01-1.08 0l-4.24-4.4a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button>
            @if ($otherLocales->isNotEmpty())
                <div data-dropdown-panel class="absolute right-0 z-50 mt-2 hidden w-32 rounded-xl border border-white/10 bg-slate-900/95 py-2 text-center shadow-lg backdrop-blur">
                    @foreach ($otherLocales as $option)
                        <form method="POST" action="{{ route('locale.switch') }}" class="m-0">
                            @csrf
                            <input type="hidden" name="locale" value="{{ $option['code'] }}">
                            <button type="submit" class="flex w-full items-center justify-center gap-2 px-2 py-2 text-lg text-white transition hover:bg-white/10"
                                aria-label="Switch to {{ $option['label'] }}">
                                <span>{{ $option['flag'] ?? '🌐' }}</span>
                                <span class="text-[10px] uppercase tracking-[0.2em] text-white/70">{{ $option['code'] }}</span>
                            </button>
                        </form>
                    @endforeach
                </div>
            @endif
        </div>

        <x-profile-dropdown :user="auth()->user()" />
    </div>
</header>
