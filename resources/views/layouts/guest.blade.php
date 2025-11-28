<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-950 text-white relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950"></div>
        <div class="absolute -top-24 -right-12 h-72 w-72 rounded-full bg-amber-400/40 blur-[150px]"></div>
        <div class="absolute -bottom-32 -left-20 h-80 w-80 rounded-full bg-sky-400/30 blur-[190px]"></div>

        <div class="relative z-10 flex min-h-screen flex-col items-center justify-center px-6 py-12">
            <div class="text-center">
                <a href="/" class="inline-flex items-center gap-4">
                    <span class="h-16 w-16 rounded-3xl bg-white/10 text-2xl font-bold tracking-wide text-amber-300 grid place-items-center border border-white/15 shadow-2xl">B</span>
                    <span class="text-left">
                        <span class="block text-3xl font-semibold tracking-wide">eBASA</span>
                        <span class="text-white/60 text-sm">Branch Access &amp; Stock Administration</span>
                    </span>
                </a>
            </div>

            <div class="w-full max-w-md mt-10 rounded-[34px] bg-white/5 p-8 shadow-2xl ring-1 ring-white/10 backdrop-blur-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
