<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — ShelfMate</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased bg-stone-100" x-data="{ sidebarOpen: false }">

    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-stone-900/60 lg:hidden"
        @click="sidebarOpen = false">
    </div>

    <div class="flex h-full min-h-screen">

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-stone-900
                  transition-transform duration-300 ease-in-out
                  lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 lg:flex lg:shrink-0">

            <div class="flex items-center gap-3 px-6 h-16 border-b border-stone-800 shrink-0">
                <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center shrink-0 shadow">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <p class="font-extrabold text-white text-sm leading-none tracking-wide">ShelfMate</p>
                    <p class="text-amber-400 text-xs font-bold tracking-widest uppercase mt-0.5">Admin Panel</p>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-5 space-y-0.5">
                <p class="px-3 pb-2 text-xs font-bold text-stone-500 uppercase tracking-widest">Menu</p>

                @php
                    $navItems = [
                        [
                            'label' => 'Dashboard',
                            'route' => 'dashboard',
                            'match' => 'dashboard',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'
                        ],
                        [
                            'label' => 'Authors',
                            'route' => 'admin.authors.index',
                            'match' => 'admin.authors.*',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>'
                        ],
                        [
                            'label' => 'Borrowers',
                            'route' => 'admin.borrowers.index',
                            'match' => 'admin.borrowers.*',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>'
                        ],
                    ];
                @endphp

                @foreach($navItems as $item)
                    @php $active = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}" @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
                                                  {{ $active ? 'bg-amber-500 text-white shadow-sm' : 'text-stone-400 hover:bg-stone-800 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            {!! $item['icon'] !!}
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach

                <div class="pt-5 border-t border-stone-800 mt-4">
                    <p class="px-3 pb-2 text-xs font-bold text-stone-500 uppercase tracking-widest">Public Site</p>
                    <a href="{{ route('home') }}" target="_blank" @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-stone-500 hover:bg-stone-800 hover:text-white transition-all">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        View Homepage
                    </a>
                    <a href="{{ route('books.index') }}" target="_blank" @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-stone-500 hover:bg-stone-800 hover:text-white transition-all">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                        Book Catalog
                    </a>
                </div>
            </nav>

            <div class="border-t border-stone-800 px-4 py-4 shrink-0">
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-8 h-8 rounded-full bg-amber-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-white text-sm font-semibold truncate leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-stone-500 text-[10px] leading-tight break-all" title="{{ Auth::user()->email }}">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('profile.edit') }}" @click="sidebarOpen = false"
                        class="flex-1 text-center text-xs font-semibold h-fit text-stone-400 hover:text-white bg-stone-800 hover:bg-stone-700 rounded-lg py-1.5 transition-colors">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full text-xs font-semibold text-stone-400 hover:text-white bg-stone-800 hover:bg-stone-700 rounded-lg py-1.5 transition-colors">
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <header
                class="sticky top-0 z-30 flex items-center gap-4 bg-white border-b border-stone-200 px-6 h-16 shrink-0 shadow-sm">
                <button @click="sidebarOpen = true"
                    class="lg:hidden text-stone-400 hover:text-stone-700 transition-colors -ml-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="flex-1 min-w-0">
                    <h1 class="text-base font-bold text-stone-800 truncate">@yield('heading')</h1>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    @yield('actions')
                </div>
            </header>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                    class="mx-6 mt-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-3 text-sm shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="flex-1">{{ session('success') }}</span>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>