<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>eBASA Portal</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            (function () {
                const storageKey = 'ebasa-theme';
                const mediaQuery = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)');
                const prefersDark = mediaQuery ? mediaQuery.matches : false;

                try {
                    const stored = localStorage.getItem(storageKey) ?? 'system';
                    if (stored === 'dark' || (stored === 'system' && prefersDark) || (!stored && prefersDark)) {
                        document.documentElement.classList.add('dark');
                    }
                } catch (error) {
                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        @livewireStyles

        <style>
            [x-cloak] { display: none !important; }

            [data-theme-options] button[data-theme-choice] {
                border: 1px solid rgba(148, 163, 184, 0.6);
                background-color: rgba(255, 255, 255, 0.2);
                color: rgb(71, 85, 105);
            }

            .dark [data-theme-options] button[data-theme-choice] {
                border-color: rgba(255, 255, 255, 0.2);
                background-color: rgba(15, 23, 42, 0.6);
                color: rgba(248, 250, 252, 0.8);
            }

            [data-theme-options] button[data-theme-choice][data-active="true"] {
                border-color: rgb(15, 23, 42);
                background-color: rgb(15, 23, 42);
                color: #fff;
            }

            .dark [data-theme-options] button[data-theme-choice][data-active="true"] {
                border-color: #fff;
                background-color: #fff;
                color: rgb(15, 23, 42);
            }
        </style>
    </head>
    <body class="font-sans antialiased min-h-screen m-0 bg-slate-50 text-slate-900 transition-colors duration-300 dark:bg-slate-950 dark:text-white">
        <div class="flex min-h-screen flex-col">
            <header class="flex items-center justify-between gap-6 px-8 py-8 lg:px-12">
                <div>
                    <p class="text-xs uppercase tracking-[0.6em] text-slate-500 dark:text-white/40">BASA</p>
                    <h1 class="text-2xl font-semibold tracking-wide text-slate-900 dark:text-white">eBASA Portal</h1>
                </div>

                <div class="flex items-center gap-4">
                    @php
                        $locales = collect(config('locale.available'));
                        $currentLocale = $locales->firstWhere('code', app()->getLocale()) ?? $locales->first();
                        $otherLocales = $locales->where('code', '!=', app()->getLocale());
                    @endphp

                    <div class="relative" data-dropdown="locale">
                        <button type="button" data-dropdown-trigger class="flex items-center gap-2 rounded-full bg-white/80 px-3 py-2 text-xl text-slate-900 shadow-sm transition dark:bg-white/10 dark:text-white">
                            <span aria-label="Current locale">{{ $currentLocale['flag'] ?? '🌐' }}</span>
                            <svg class="h-3.5 w-3.5 text-slate-500 dark:text-white/70" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.084l3.71-3.854a.75.75 0 011.08 1.04l-4.24 4.4a.75.75 0 01-1.08 0l-4.24-4.4a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        @if ($otherLocales->isNotEmpty())
                            <div data-dropdown-panel class="absolute right-0 mt-2 hidden w-32 rounded-xl border border-slate-200 bg-white py-2 text-center shadow-lg backdrop-blur dark:border-white/10 dark:bg-slate-900/95">
                                @foreach ($otherLocales as $option)
                                    <form method="POST" action="{{ route('locale.switch') }}" class="m-0">
                                        @csrf
                                        <input type="hidden" name="locale" value="{{ $option['code'] }}">
                                        <button type="submit" class="flex w-full items-center justify-center gap-2 px-2 py-2 text-lg text-slate-700 transition hover:bg-slate-100 dark:text-white dark:hover:bg-white/10"
                                            aria-label="Switch to {{ $option['label'] }}">
                                            <span>{{ $option['flag'] ?? '🌐' }}</span>
                                            <span class="text-[10px] uppercase tracking-[0.2em] text-slate-500 dark:text-white/70">{{ $option['code'] }}</span>
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <x-profile-dropdown :user="auth()->user()" />
                </div>
            </header>

            <main class="flex flex-1 flex-col items-center justify-center text-center px-8 lg:px-12">
                <div>
                    <p class="text-sm uppercase tracking-[0.6em] text-slate-500 dark:text-white/50">{{ __('home.hero.brand_tag') }}</p>
                    <h2 class="mt-4 text-4xl font-semibold text-slate-900 dark:text-white">{{ __('home.hero.cta') }}</h2>
                    <p class="mt-2 text-slate-600 dark:text-white/70">{{ __('home.hero.subtitle') }}</p>
                </div>

                <div class="mt-16 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                    @forelse ($modules as $module)
                        @php
                            $moduleName = $module['name_key'] ?? null;
                            $moduleDescription = $module['description_key'] ?? null;
                            $statusKey = $module['status'] ?? null;
                        @endphp
                        <a href="{{ url($module['url']) }}"
                            class="group relative flex h-52 w-52 flex-col items-center justify-center rounded-[36px] bg-gradient-to-br {{ $module['color'] ?? 'from-white/10 to-white/5 text-white' }} text-center shadow-[0_25px_45px_rgba(15,23,42,0.35)] transition hover:-translate-y-1">
                            <span class="text-5xl">{{ $module['icon'] ?? '🧩' }}</span>
                            <span class="mt-4 text-lg font-semibold">
                                {{ $moduleName ? __($moduleName) : $module['name'] }}
                            </span>
                            <span class="text-xs uppercase tracking-[0.2em] text-white/70">
                                {{ $statusKey ? __('home.modules.status.' . $statusKey) : ($module['status'] ?? __('home.modules.status.coming-soon')) }}
                            </span>
                            <span class="mt-2 text-xs text-white/70 max-w-[11rem]">
                                {{ $moduleDescription ? __($moduleDescription) : ($module['description'] ?? '') }}
                            </span>
                        </a>
                    @empty
                        <div class="text-white/60 text-sm">No modules configured yet.</div>
                    @endforelse
                </div>
            </main>

            <div class="px-8 pb-8 lg:px-12 flex flex-wrap items-center justify-center gap-6 text-xs text-slate-500 dark:text-white/50">
                <span>
                    {{ config('app.name', 'eBASA Portal') }} · v0.0.1 ·
                    {{ __('home.footer.current_locale', ['locale' => strtoupper(app()->getLocale())]) }} ·
                    {{ optional($jakartaNow)->format('D, d M Y · H:i') ?? now('Asia/Jakarta')->format('D, d M Y · H:i') }} WIB
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-slate-900 dark:hover:text-white">{{ __('home.footer.logout') }}</button>
                </form>
            </div>
        </div>

        <x-floating-branch-switcher :branch-shortcuts="$branchShortcuts" :active-branch-id="$activeBranchId" />

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const dropdowns = Array.from(document.querySelectorAll('[data-dropdown]'));
                const themeOptions = document.querySelector('[data-theme-options]');
                const themeButtons = themeOptions ? Array.from(themeOptions.querySelectorAll('[data-theme-choice]')) : [];
                const storageKey = 'ebasa-theme';
                const mediaQuery = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)');

                const closeAll = (except = null) => {
                    dropdowns.forEach((dropdown) => {
                        if (dropdown !== except) {
                            dropdown.dataset.open = 'false';
                            dropdown.querySelector('[data-dropdown-panel]')?.classList.add('hidden');
                        }
                    });
                };

                dropdowns.forEach((dropdown) => {
                    const trigger = dropdown.querySelector('[data-dropdown-trigger]');
                    const panel = dropdown.querySelector('[data-dropdown-panel]');
                    if (!trigger || !panel) {
                        return;
                    }

                    trigger.addEventListener('click', (event) => {
                        event.stopPropagation();
                        const isOpen = dropdown.dataset.open === 'true';
                        closeAll();
                        if (!isOpen) {
                            dropdown.dataset.open = 'true';
                            panel.classList.remove('hidden');
                        }
                    });
                });

                document.addEventListener('click', () => closeAll());

                const getStoredTheme = () => {
                    try {
                        return localStorage.getItem(storageKey) || 'system';
                    } catch (error) {
                        return 'system';
                    }
                };

                const setStoredTheme = (mode) => {
                    try {
                        localStorage.setItem(storageKey, mode);
                    } catch (error) {
                        // ignore storage errors
                    }
                };

                const prefersDark = () => (mediaQuery ? mediaQuery.matches : false);

                const applyThemeState = (mode) => {
                    const effectiveMode = mode || getStoredTheme();
                    const isDark = effectiveMode === 'dark' || (effectiveMode === 'system' && prefersDark());
                    document.documentElement.classList.toggle('dark', isDark);
                    themeButtons.forEach((button) => {
                        button.dataset.active = button.dataset.themeChoice === effectiveMode ? 'true' : 'false';
                    });
                };

                const initialTheme = getStoredTheme();
                applyThemeState(initialTheme);

                themeButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        const choice = button.dataset.themeChoice || 'system';
                        setStoredTheme(choice);
                        applyThemeState(choice);
                    });
                });

                mediaQuery?.addEventListener('change', () => {
                    if (getStoredTheme() === 'system') {
                        applyThemeState('system');
                    }
                });
            });
        </script>

        @livewireScripts
    </body>
</html>
