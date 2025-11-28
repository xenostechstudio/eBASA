<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $pageTitle ?? 'BASA POS' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased min-h-screen overflow-hidden bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-white">
        <div class="flex min-h-screen flex-col overflow-hidden">
            <main class="flex flex-1 overflow-hidden p-4 lg:p-6">
                <div class="flex flex-1 w-full overflow-hidden">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @livewireScripts
    </body>
</html>
