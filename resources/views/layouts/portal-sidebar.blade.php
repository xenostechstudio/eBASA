<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $pageTitle ?? 'eBASA Portal' }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <script>
            (function () {
                const storageKey = 'ebasa-theme';
                const mediaQuery = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)');
                const prefersDark = mediaQuery ? mediaQuery.matches : false;

                try {
                    const stored = localStorage.getItem(storageKey) ?? 'system';
                    if (stored === 'dark' || (stored === 'system' && prefersDark) || (!stored && prefersDark)) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                } catch (error) {
                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>
    </head>
    <body class="font-sans antialiased min-h-screen m-0 bg-slate-100 text-slate-900 transition-colors duration-300 dark:bg-slate-950 dark:text-white">
        @php
            $branchShortcuts = \App\Models\Branch::query()->orderBy('name')->get();
            $activeBranchId = (int) session('active_branch_id');
        @endphp

        <div
            class="flex min-h-screen"
            x-data="{
                activeModule: '{{ $activeModule ?? '' }}',
                expanded: {{ ($activeModule ?? null) ? 'true' : 'false' }},
                setModule(key) {
                    if (this.activeModule === key) {
                        this.expanded = !this.expanded;
                    } else {
                        this.activeModule = key;
                        this.expanded = true;
                    }
                }
            }"
        >
            {{-- Sidebar Navigation --}}
            <x-sidebar-nav
                :active-module="$activeModule ?? null"
                :nav-links="$navLinks ?? []"
            />

            {{-- Main Content Area --}}
            <main
                class="flex-1 min-w-0 transition-all duration-200"
                :style="'margin-left: ' + (expanded ? '312px' : '88px')"
            >
                {{-- Page Header --}}
                <header class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-200 bg-white/80 px-6 py-4 backdrop-blur dark:border-white/10 dark:bg-slate-900/80">
                    <div>
                        @if (! empty($pageTagline ?? ''))
                            <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400 dark:text-white/40">{{ $pageTagline }}</p>
                        @endif
                        <h1 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $pageTitle ?? 'Dashboard' }}</h1>
                    </div>

                    <div class="flex items-center gap-3">
                        {{-- Theme Toggle --}}
                        <div class="flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 p-1 dark:border-white/10 dark:bg-white/5">
                            <button
                                type="button"
                                data-theme-choice="light"
                                class="rounded-full p-1.5 transition"
                                data-active="false"
                            >
                                @svg('heroicon-o-sun', 'h-4 w-4')
                            </button>
                            <button
                                type="button"
                                data-theme-choice="system"
                                class="rounded-full p-1.5 transition"
                                data-active="false"
                            >
                                @svg('heroicon-o-computer-desktop', 'h-4 w-4')
                            </button>
                            <button
                                type="button"
                                data-theme-choice="dark"
                                class="rounded-full p-1.5 transition"
                                data-active="false"
                            >
                                @svg('heroicon-o-moon', 'h-4 w-4')
                            </button>
                        </div>

                        {{ $headerActions ?? '' }}
                    </div>
                </header>

                {{-- Page Content --}}
                <div class="p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <x-floating-branch-switcher :branch-shortcuts="$branchShortcuts" :active-branch-id="$activeBranchId" />

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Theme toggle
                const themeButtons = Array.from(document.querySelectorAll('[data-theme-choice]'));
                const storageKey = 'ebasa-theme';
                const mediaQuery = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)');

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
                        const isActive = button.dataset.themeChoice === effectiveMode;
                        button.dataset.active = isActive ? 'true' : 'false';
                        button.classList.toggle('bg-white', isActive);
                        button.classList.toggle('shadow-sm', isActive);
                        button.classList.toggle('text-slate-900', isActive);
                        button.classList.toggle('dark:bg-white/20', isActive);
                        button.classList.toggle('dark:text-white', isActive);
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
