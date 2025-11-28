@props(['pageTitle' => 'eBASA Portal'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $pageTitle }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-slate-950 text-white min-h-screen m-0">
        <div class="flex min-h-screen flex-col">
            <x-portal-header />

            <div class="flex flex-1 flex-col gap-6 px-8 pb-10 lg:flex-row lg:px-12">
                <aside class="rounded-[32px] border border-white/10 bg-white/5 p-6 backdrop-blur lg:w-72">
                    {{ $sidebar ?? view('components.portal-sidebar-placeholder') }}
                </aside>

                <section class="flex-1 rounded-[32px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                    {{ $slot }}
                </section>
            </div>
        </div>

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
            });
        </script>

        @livewireScripts
    </body>
</html>
