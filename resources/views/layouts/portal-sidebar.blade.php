<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $pageTitle ?? 'eBASA Portal' }}</title>

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
    <body class="font-sans antialiased min-h-screen m-0 bg-slate-100 text-slate-800 transition-colors duration-300 dark:bg-slate-950 dark:text-white">
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
                :style="'margin-left: ' + (expanded ? '344px' : '88px')"
            >
                {{-- Page Header --}}
                <header class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-300 bg-white/90 px-6 py-4 shadow-sm backdrop-blur dark:border-white/10 dark:bg-slate-900/80">
                    <div>
                        @if (! empty($pageTagline ?? ''))
                            <p class="text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-400 dark:text-white/40">{{ $pageTagline }}</p>
                        @endif
                        <h1 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $pageTitle ?? 'Dashboard' }}</h1>
                    </div>

                    <div class="flex items-center gap-3">
                        {{ $headerActions ?? '' }}
                    </div>
                </header>

                {{-- Page Content --}}
                <div class="p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @if (($activeModule ?? null) !== 'general-setup')
            <x-floating-branch-switcher :branch-shortcuts="$branchShortcuts" :active-branch-id="$activeBranchId" />
        @endif

        {{-- Global notifications for Livewire dispatch('notify', ...) events --}}
        <div
            x-data="{ notifications: [] }"
            x-on:notify.window="
                if (! $event.detail || ! $event.detail.message) return;
                notifications.push({
                    id: Date.now(),
                    message: $event.detail.message,
                });
            "
        >
            <template x-for="notification in notifications" :key="notification.id">
                <x-alert type="success">
                    <span x-text="notification.message"></span>
                </x-alert>
            </template>
        </div>

        @livewireScripts

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const branchSwitcher = document.querySelector('[data-branch-switcher]');

                if (!branchSwitcher) {
                    return;
                }

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
            });
        </script>
    </body>
</html>
