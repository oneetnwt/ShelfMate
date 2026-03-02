<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ShelfMate') &mdash; Your Community Library</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }

        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .book-spine {
            background: linear-gradient(to right, rgba(0, 0, 0, 0.15) 0%, transparent 10%, transparent 90%, rgba(0, 0, 0, 0.1) 100%);
        }

        .genre-badge {
            font-size: 0.65rem;
            letter-spacing: 0.05em;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .toast-enter {
            animation: fadeIn 0.25s ease forwards;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-stone-50 text-stone-900 antialiased min-h-screen flex flex-col">

    {{-- Flash toast --}}
    @if (session('success'))
        <div id="flash-success" class="fixed top-4 left-1/2 -translate-x-1/2 z-50 bg-green-700 text-white text-sm font-medium
                       px-5 py-3 rounded-xl shadow-xl flex items-center gap-2 toast-enter max-w-prose text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- NAVBAR --}}
    <nav class="bg-white border-b border-stone-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                    <div class="w-9 h-9 bg-amber-600 rounded-lg flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-stone-800 tracking-tight">ShelfMate</span>
                </a>

                {{-- Links --}}
                <div class="flex items-center gap-1 text-sm font-medium">
                    <a href="{{ route('home') }}"
                        class="hidden sm:block px-3 py-2 rounded-lg transition-colors
                               {{ request()->routeIs('home') ? 'text-amber-700 bg-amber-50' : 'text-stone-600 hover:text-amber-700 hover:bg-amber-50' }}">
                        Browse
                    </a>
                    <a href="{{ route('books.index') }}"
                        class="hidden sm:block px-3 py-2 rounded-lg transition-colors
                               {{ request()->routeIs('books.*') ? 'text-amber-700 bg-amber-50' : 'text-stone-600 hover:text-amber-700 hover:bg-amber-50' }}">
                        Catalog
                    </a>
                    <a href="{{ route('borrow.create') }}"
                        class="hidden sm:block px-3 py-2 rounded-lg transition-colors
                               {{ request()->routeIs('borrow.create') ? 'text-amber-700 bg-amber-50' : 'text-stone-600 hover:text-amber-700 hover:bg-amber-50' }}">
                        Borrow
                    </a>
                    <a href="{{ route('borrow.my-borrows') }}" class="inline-flex items-center gap-1.5 bg-amber-600 hover:bg-amber-700 text-white
                               px-4 py-2 rounded-lg font-semibold transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        My Borrows
                    </a>
                    @auth
                        <div class="hidden sm:flex items-center gap-1 pl-3 ml-1 border-l border-stone-200">
                            <a href="{{ url('/dashboard') }}"
                                class="px-3 py-2 rounded-lg text-stone-600 hover:text-amber-700 hover:bg-amber-50 transition-colors">
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-2 rounded-lg text-stone-500 hover:text-red-600 hover:bg-red-50 transition-colors">
                                    Log out
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-1">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-stone-200 bg-white mt-12">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-stone-600">
                <div class="w-7 h-7 bg-amber-600 rounded-md flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="font-semibold">ShelfMate</span>
            </div>
            <p class="text-stone-400 text-sm text-center">
                Return period: 14 days &bull; Fine: &#8369;10/day per overdue book &bull; No account required
            </p>
            <p class="text-stone-400 text-sm">&copy; {{ date('Y') }} ShelfMate</p>
        </div>
    </footer>

    @stack('scripts')

    <script>
        // Auto-dismiss flash toast after 4 s
        setTimeout(function () {
            const el = document.getElementById('flash-success');
            if (el) {
                el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                el.style.opacity = '0';
                el.style.transform = 'translate(-50%, -8px)';
                setTimeout(() => el.remove(), 400);
            }
        }, 4000);

        // Hidden admin login: type "admin" anywhere (not inside an input)
        (function () {
            const seq = 'admin'; let buf = ''; let t = null;
            document.addEventListener('keydown', function (e) {
                const tag = document.activeElement.tagName.toLowerCase();
                if (tag === 'input' || tag === 'textarea' || tag === 'select') return;
                buf += e.key.toLowerCase();
                if (!seq.startsWith(buf)) buf = e.key.toLowerCase();
                clearTimeout(t);
                t = setTimeout(() => { buf = ''; }, 2000);
                if (buf === seq) { buf = ''; clearTimeout(t); window.location.href = '/admin/login'; }
            });
        })();
    </script>
</body>

</html>