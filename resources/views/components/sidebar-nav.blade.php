@props([
    'activeModule' => null,
    'navLinks' => [],
])

@php
    $modules = collect(config('modules.list', []));

    $modules = $modules->sortBy(function ($module) {
        $order = [
            'pos' => 10,
            'transactions' => 20,
            'inventory' => 30,
            'procurement' => 35,
            'hr' => 40,
            'reports' => 50,
            'general-setup' => 60,
        ];

        return $order[$module['key']] ?? 999;
    })->values();

    $moduleIcons = [
        'pos' => 'heroicon-o-shopping-cart',
        'transactions' => 'heroicon-o-credit-card',
        'inventory' => 'heroicon-o-cube',
        'hr' => 'heroicon-o-users',
        'reports' => 'heroicon-o-chart-bar',
        'general-setup' => 'heroicon-o-cog-6-tooth',
        'procurement' => 'heroicon-o-truck',
    ];

    $moduleShortNames = [
        'pos' => 'POS',
        'transactions' => 'Trans',
        'inventory' => 'Inventory',
        'hr' => 'HR',
        'reports' => 'Reports',
        'general-setup' => 'Setup',
        'procurement' => 'Procure',
    ];

    $locales = collect(config('locale.available'));
    $currentLocale = $locales->firstWhere('code', app()->getLocale()) ?? $locales->first();
    $otherLocales = $locales->where('code', '!=', app()->getLocale());

    // Branch switcher data
    $branches = \App\Models\Branch::query()->orderBy('name')->get();
    $activeBranchId = (int) session('active_branch_id');
    $activeBranch = $branches->firstWhere('id', $activeBranchId);
@endphp

<aside class="fixed inset-y-0 left-0 z-30 flex">
    {{-- Icon Rail --}}
    <div class="flex h-full w-[88px] flex-col items-center border-r border-slate-300 bg-white py-4 dark:border-white/10 dark:bg-slate-950">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="mb-6 flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-slate-800 to-slate-900 text-white shadow-lg dark:from-white dark:to-slate-200 dark:text-slate-900">
            <span class="text-sm font-bold">B</span>
        </a>

        {{-- Module Icons --}}
        <nav class="flex flex-1 flex-col items-center gap-1">
            @foreach ($modules as $module)
                <a
                    href="{{ $module['url'] }}"
                    wire:navigate
                    @click="setModule('{{ $module['key'] }}')"
                    class="group relative flex h-16 w-[72px] flex-col items-center justify-center rounded-xl transition {{ $activeModule === $module['key'] ? 'bg-slate-900 text-white shadow-sm dark:bg-white dark:text-slate-900' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-white/40 dark:hover:bg-white/5 dark:hover:text-white/80' }}"
                    aria-label="{{ $module['name'] }}"
                >
                    @if (isset($moduleIcons[$module['key']]))
                        @svg($moduleIcons[$module['key']], 'h-7 w-7')
                    @else
                        <span class="text-2xl">{{ $module['icon'] }}</span>
                    @endif
                    <span class="mt-1 text-[10px] font-medium leading-tight text-center {{ $activeModule === $module['key'] ? 'text-slate-100 dark:text-slate-900' : 'text-slate-400 dark:text-white/40' }}">
                        {{ $moduleShortNames[$module['key']] ?? Str::limit($module['name'], 8, '') }}
                    </span>
                </a>
            @endforeach
        </nav>

        {{-- Bottom: Profile Only --}}
        <div class="mt-auto flex flex-col items-center gap-3 pb-4">
            {{-- Profile with Language & Theme sub-dropdowns --}}
            <div class="relative" x-data="{ 
                open: false, 
                submenu: null,
                theme: localStorage.getItem('ebasa-theme') || 'system',
                setTheme(mode) {
                    this.theme = mode;
                    localStorage.setItem('ebasa-theme', mode);
                    if (mode === 'dark' || (mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                },
                closeAll() {
                    this.open = false;
                    this.submenu = null;
                }
            }">
                <button
                    type="button"
                    @click="open = !open; submenu = null"
                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-slate-700 to-slate-900 text-sm font-semibold text-white shadow-sm transition hover:from-slate-600 hover:to-slate-800 dark:from-white/20 dark:to-white/10 dark:hover:from-white/30 dark:hover:to-white/20"
                    :class="open ? 'ring-2 ring-slate-400 ring-offset-2 dark:ring-white/30 dark:ring-offset-slate-950' : ''"
                    aria-label="Profile menu"
                >
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </button>

                {{-- Main Dropdown --}}
                <div
                    x-cloak
                    x-show="open"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    @click.away="closeAll()"
                    class="absolute bottom-0 left-full z-50 ml-3 w-56 origin-bottom-left rounded-2xl border border-slate-300 bg-white p-2 shadow-xl dark:border-white/10 dark:bg-slate-900"
                >
                    {{-- User Info Header --}}
                    <div class="mb-2 rounded-xl bg-gradient-to-br from-slate-50 to-slate-100 p-3 dark:from-white/5 dark:to-white/10">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-slate-700 to-slate-900 text-sm font-semibold text-white dark:from-white/20 dark:to-white/10">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name ?? 'User' }}</p>
                                <p class="truncate text-xs text-slate-500 dark:text-white/60">{{ auth()->user()->email ?? '' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Menu Items --}}
                    <div class="space-y-0.5">
                        <a href="{{ route('profile.edit') }}" @click="closeAll()" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50 dark:text-white/80 dark:hover:bg-white/5">
                            @svg('heroicon-o-user-circle', 'h-5 w-5 text-slate-400 dark:text-white/40')
                            <span>My Profile</span>
                        </a>

                        {{-- Language Sub-menu Trigger --}}
                        <button
                            type="button"
                            @click="submenu = submenu === 'language' ? null : 'language'"
                            class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50 dark:text-white/80 dark:hover:bg-white/5"
                            :class="submenu === 'language' ? 'bg-slate-50 dark:bg-white/5' : ''"
                        >
                            <span class="flex items-center gap-3">
                                @svg('heroicon-o-language', 'h-5 w-5 text-slate-400 dark:text-white/40')
                                <span>Language</span>
                            </span>
                            <span class="flex items-center gap-2">
                                <span class="text-xs text-slate-400 dark:text-white/40">{{ $currentLocale['flag'] ?? '🌐' }} {{ strtoupper($currentLocale['code'] ?? 'EN') }}</span>
                                @svg('heroicon-o-chevron-right', 'h-4 w-4 text-slate-400 dark:text-white/40')
                            </span>
                        </button>

                        {{-- Theme Sub-menu Trigger --}}
                        <button
                            type="button"
                            @click="submenu = submenu === 'theme' ? null : 'theme'"
                            class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50 dark:text-white/80 dark:hover:bg-white/5"
                            :class="submenu === 'theme' ? 'bg-slate-50 dark:bg-white/5' : ''"
                        >
                            <span class="flex items-center gap-3">
                                <template x-if="theme === 'light'">@svg('heroicon-o-sun', 'h-5 w-5 text-slate-400 dark:text-white/40')</template>
                                <template x-if="theme === 'dark'">@svg('heroicon-o-moon', 'h-5 w-5 text-slate-400 dark:text-white/40')</template>
                                <template x-if="theme === 'system'">@svg('heroicon-o-computer-desktop', 'h-5 w-5 text-slate-400 dark:text-white/40')</template>
                                <span>Theme</span>
                            </span>
                            <span class="flex items-center gap-2">
                                <span class="text-xs capitalize text-slate-400 dark:text-white/40" x-text="theme === 'system' ? 'Auto' : theme"></span>
                                @svg('heroicon-o-chevron-right', 'h-4 w-4 text-slate-400 dark:text-white/40')
                            </span>
                        </button>

                        {{-- Branch Sub-menu Trigger --}}
                        <button
                            type="button"
                            @click="submenu = submenu === 'branch' ? null : 'branch'"
                            class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50 dark:text-white/80 dark:hover:bg-white/5"
                            :class="submenu === 'branch' ? 'bg-slate-50 dark:bg-white/5' : ''"
                        >
                            <span class="flex items-center gap-3">
                                @svg('heroicon-o-building-storefront', 'h-5 w-5 text-slate-400 dark:text-white/40')
                                <span>Branch</span>
                            </span>
                            <span class="flex items-center gap-2">
                                <span class="text-xs text-slate-400 dark:text-white/40">{{ $activeBranch?->name ?? 'All' }}</span>
                                @svg('heroicon-o-chevron-right', 'h-4 w-4 text-slate-400 dark:text-white/40')
                            </span>
                        </button>
                    </div>

                    {{-- Divider --}}
                    <div class="my-2 border-t border-slate-100 dark:border-white/10"></div>

                    {{-- Sign Out --}}
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                            @svg('heroicon-o-arrow-right-on-rectangle', 'h-5 w-5')
                            <span>Sign out</span>
                        </button>
                    </form>
                </div>

                {{-- Language Sub-dropdown --}}
                <div
                    x-cloak
                    x-show="submenu === 'language'"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-2"
                    class="absolute bottom-0 left-full z-50 ml-[15.5rem] w-44 origin-bottom-left rounded-2xl border border-slate-300 bg-white p-2 shadow-xl dark:border-white/10 dark:bg-slate-900"
                >
                    <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Select Language</p>
                    <div class="space-y-0.5">
                        @foreach ($locales as $option)
                            <form method="POST" action="{{ route('locale.switch') }}" class="m-0">
                                @csrf
                                <input type="hidden" name="locale" value="{{ $option['code'] }}">
                                <button
                                    type="submit"
                                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition {{ app()->getLocale() === $option['code'] ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'text-slate-700 hover:bg-slate-50 dark:text-white/80 dark:hover:bg-white/5' }}"
                                >
                                    <span class="text-lg">{{ $option['flag'] ?? '🌐' }}</span>
                                    <span>{{ $option['name'] ?? strtoupper($option['code']) }}</span>
                                    @if(app()->getLocale() === $option['code'])
                                        @svg('heroicon-o-check', 'ml-auto h-4 w-4')
                                    @endif
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>

                {{-- Theme Sub-dropdown --}}
                <div
                    x-cloak
                    x-show="submenu === 'theme'"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-2"
                    class="absolute bottom-0 left-full z-50 ml-[15.5rem] w-44 origin-bottom-left rounded-2xl border border-slate-300 bg-white p-2 shadow-xl dark:border-white/10 dark:bg-slate-900"
                >
                    <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Select Theme</p>
                    <div class="space-y-0.5">
                        <button
                            type="button"
                            @click="setTheme('light'); submenu = null"
                            class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition"
                            :class="theme === 'light' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'text-slate-700 hover:bg-slate-50 dark:text-white/80 dark:hover:bg-white/5'"
                        >
                            @svg('heroicon-o-sun', 'h-5 w-5')
                            <span>Light</span>
                            <template x-if="theme === 'light'">@svg('heroicon-o-check', 'ml-auto h-4 w-4')</template>
                        </button>
                        <button
                            type="button"
                            @click="setTheme('dark'); submenu = null"
                            class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition"
                            :class="theme === 'dark' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'text-slate-700 hover:bg-slate-50 dark:text-white/80 dark:hover:bg-white/5'"
                        >
                            @svg('heroicon-o-moon', 'h-5 w-5')
                            <span>Dark</span>
                            <template x-if="theme === 'dark'">@svg('heroicon-o-check', 'ml-auto h-4 w-4')</template>
                        </button>
                        <button
                            type="button"
                            @click="setTheme('system'); submenu = null"
                            class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition"
                            :class="theme === 'system' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'text-slate-700 hover:bg-slate-50 dark:text-white/80 dark:hover:bg-white/5'"
                        >
                            @svg('heroicon-o-computer-desktop', 'h-5 w-5')
                            <span>Auto (System)</span>
                            <template x-if="theme === 'system'">@svg('heroicon-o-check', 'ml-auto h-4 w-4')</template>
                        </button>
                    </div>
                </div>

                {{-- Branch Sub-dropdown --}}
                <div
                    x-cloak
                    x-show="submenu === 'branch'"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-2"
                    class="absolute bottom-0 left-full z-50 ml-[15.5rem] w-48 origin-bottom-left rounded-2xl border border-slate-300 bg-white p-2 shadow-xl dark:border-white/10 dark:bg-slate-900"
                >
                    <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">Select Branch</p>
                    <div class="max-h-64 space-y-0.5 overflow-y-auto">
                        {{-- All Branches Option --}}
                        <form method="POST" action="{{ route('branch.switch') }}" class="m-0">
                            @csrf
                            <input type="hidden" name="branch_id" value="all">
                            <button
                                type="submit"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition {{ !$activeBranchId ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'text-slate-700 hover:bg-slate-50 dark:text-white/80 dark:hover:bg-white/5' }}"
                            >
                                @svg('heroicon-o-globe-alt', 'h-5 w-5')
                                <span>All Branches</span>
                                @if (!$activeBranchId)
                                    @svg('heroicon-o-check', 'ml-auto h-4 w-4')
                                @endif
                            </button>
                        </form>

                        {{-- Individual Branches --}}
                        @foreach ($branches as $branch)
                            <form method="POST" action="{{ route('branch.switch') }}" class="m-0">
                                @csrf
                                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                <button
                                    type="submit"
                                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition {{ $activeBranchId === $branch->id ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'text-slate-700 hover:bg-slate-50 dark:text-white/80 dark:hover:bg-white/5' }}"
                                >
                                    @svg('heroicon-o-building-storefront', 'h-5 w-5')
                                    <span class="truncate">{{ $branch->name }}</span>
                                    @if ($activeBranchId === $branch->id)
                                        @svg('heroicon-o-check', 'ml-auto h-4 w-4 flex-shrink-0')
                                    @endif
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Expandable Sub-Nav Panel --}}
    <div
        x-cloak
        x-show="expanded"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="-translate-x-full opacity-0"
        class="h-full w-64 border-r border-slate-300 bg-white dark:border-white/10 dark:bg-slate-900/80"
    >
        <div class="flex h-full flex-col p-4">
            {{-- Module Title --}}
            <div class="mb-4">
                <template x-for="mod in {{ $modules->toJson() }}" :key="mod.key">
                    <div x-show="activeModule === mod.key">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400 dark:text-white/40">Module</p>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white" x-text="mod.name"></h2>
                    </div>
                </template>
            </div>

            {{-- Sub Navigation Links --}}
            <nav class="flex-1 space-y-1">
                @if (! empty($navLinks))
                    @foreach ($navLinks as $link)
                        @if (! empty($link['children']))
                            <div class="space-y-1">
                                <p class="px-3 py-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-white/40">
                                    {{ $link['label'] }}
                                </p>
                                @foreach ($link['children'] as $child)
                                    <a
                                        href="{{ $child['href'] ?? '#' }}"
                                        wire:navigate
                                        class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm transition {{ ($child['active'] ?? false) ? 'bg-slate-900 text-white shadow-sm dark:bg-white/10 dark:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-white/70 dark:hover:bg-white/5 dark:hover:text-white' }}"
                                    >
                                        @if (! empty($child['icon'] ?? null))
                                            @svg($child['icon'], 'h-4 w-4')
                                        @endif
                                        <span>{{ $child['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <a
                                href="{{ $link['href'] ?? '#' }}"
                                wire:navigate
                                class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm transition {{ ($link['active'] ?? false) ? 'bg-slate-900 text-white shadow-sm dark:bg-white/10 dark:text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-white/70 dark:hover:bg-white/5 dark:hover:text-white' }}"
                            >
                                @if (! empty($link['icon'] ?? null))
                                    @svg($link['icon'], 'h-4 w-4')
                                @endif
                                <span>{{ $link['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                @else
                    <p class="px-3 py-2 text-xs text-slate-400 dark:text-white/40">Select a module to see navigation</p>
                @endif
            </nav>

            {{-- Collapse Button --}}
            <button
                type="button"
                @click="expanded = false"
                class="mt-4 flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs text-slate-600 transition hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-white/60 dark:hover:bg-white/10"
            >
                @svg('heroicon-o-chevron-left', 'h-4 w-4')
                <span>Collapse</span>
            </button>
        </div>
    </div>
</aside>
