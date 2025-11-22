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
    <body class="font-sans antialiased min-h-screen m-0 bg-slate-50 text-slate-900 transition-colors duration-300 dark:bg-slate-950 dark:text-white">
        @php
            $branchShortcuts = \App\Models\Branch::query()->orderBy('name')->get();
            $activeBranchId = (int) session('active_branch_id');
        @endphp

        <div class="flex min-h-screen flex-col">
            <x-portal-header
                :title="$pageTitle ?? 'eBASA Portal'"
                :show-brand="$showBrand ?? true"
                :show-home-icon="$showHomeIcon ?? true"
                :nav-links="$navLinks ?? []"
            />

            <div class="flex flex-1 flex-col px-8 pb-10 lg:px-12">
                <section class="flex h-full flex-1 rounded-[32px] border border-slate-200/60 bg-white/80 p-6 backdrop-blur dark:border-white/10 dark:bg-white/5">
                    <div class="flex h-full w-full flex-col overflow-hidden">
                        {{ $slot }}
                    </div>
                </section>
            </div>
        </div>

        <x-floating-branch-switcher :branch-shortcuts="$branchShortcuts" :active-branch-id="$activeBranchId" />

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const dropdowns = Array.from(document.querySelectorAll('[data-dropdown]'));

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

                const branchSwitcher = document.querySelector('[data-branch-switcher]');
                if (branchSwitcher) {
                    const trigger = branchSwitcher.querySelector('[data-branch-trigger]');
                    const panel = branchSwitcher.querySelector('[data-branch-panel]');
                    const togglePanel = (show) => {
                        if (!panel) return;
                        if (show) {
                            panel.classList.remove('hidden');
                        } else {
                            panel.classList.add('hidden');
                        }
                    };

                    trigger?.addEventListener('click', (event) => {
                        event.stopPropagation();
                        const isHidden = panel?.classList.contains('hidden');
                        togglePanel(isHidden);
                    });

                    document.addEventListener('click', (event) => {
                        if (!branchSwitcher.contains(event.target)) {
                            togglePanel(false);
                        }
                    });
                }

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
