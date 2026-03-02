<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ShelfMate') }} &mdash; Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>

<body class="antialiased bg-stone-50 text-stone-900 min-h-screen">

    <div class="min-h-screen flex">

        {{-- Left panel: brand (hidden on mobile) --}}
        <div
            class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-amber-700 via-amber-600 to-orange-500 flex-col items-center justify-center p-12 text-white relative overflow-hidden">
            {{-- Decorative circles --}}
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-16 -right-16 w-96 h-96 bg-black/10 rounded-full"></div>

            <div class="relative z-10 text-center">
                {{-- Logo --}}
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-white" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight mb-3">ShelfMate</h1>
                <p class="text-amber-100 text-lg leading-relaxed max-w-xs mx-auto">
                    Admin portal &mdash; manage your library collection, borrows, and members.
                </p>

                <div class="mt-10 flex flex-col gap-3 text-left max-w-xs mx-auto">
                    <div class="flex items-center gap-3 text-sm text-amber-100">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        Manage the full book catalogue
                    </div>
                    <div class="flex items-center gap-3 text-sm text-amber-100">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        Track active borrows &amp; returns
                    </div>
                    <div class="flex items-center gap-3 text-sm text-amber-100">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        View reports &amp; statistics
                    </div>
                </div>
            </div>
        </div>

        {{-- Right panel: form --}}
        <div class="flex-1 flex flex-col items-center justify-center p-6 sm:p-12">

            {{-- Mobile logo --}}
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-amber-600 rounded-xl flex items-center justify-center shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="text-xl font-bold text-stone-800">ShelfMate</span>
            </div>

            <div class="w-full max-w-md">
                {{ $slot }}
            </div>

            <p class="mt-8 text-xs text-stone-400">
                &larr; <a href="/" class="hover:text-amber-600 transition-colors">Back to library</a>
            </p>
        </div>
    </div>

</body>

</html>