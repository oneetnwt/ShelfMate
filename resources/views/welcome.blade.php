<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ShelfMate &mdash; Your Community Library</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        <style>
            body { font-family: 'Figtree', sans-serif; }
            .book-spine { background: linear-gradient(to right, rgba(0,0,0,0.15) 0%, transparent 10%, transparent 90%, rgba(0,0,0,0.1) 100%); }
            .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
            .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.12); }
            .genre-badge { font-size: 0.65rem; letter-spacing: 0.05em; }
            @keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
            .toast-enter { animation: fadeIn 0.2s ease forwards; }
        </style>
    </head>
    <body class="bg-stone-50 text-stone-900 antialiased min-h-screen">

        {{-- NAVBAR --}}
        <nav class="bg-white border-b border-stone-200 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">

                    {{-- Logo --}}
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-amber-600 rounded-lg flex items-center justify-center shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-stone-800 tracking-tight">ShelfMate</span>
                    </div>

                    {{-- Search (desktop) --}}
                    <div class="hidden md:flex flex-1 max-w-md mx-8">
                        <div class="relative w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" placeholder="Search by title, author, or genre…"
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-stone-200 bg-stone-50 text-sm text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                        </div>
                    </div>

                    {{-- Nav links --}}
                    <div class="flex items-center gap-4 text-sm font-medium">
                        <a href="#books" class="text-stone-600 hover:text-amber-700 transition-colors hidden sm:block">Browse</a>
                        <a href="#how-it-works" class="text-stone-600 hover:text-amber-700 transition-colors hidden sm:block">How it works</a>
                        <a href="#" class="inline-flex items-center gap-1.5 bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            My Borrows
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        {{-- HERO --}}
        <section class="bg-gradient-to-br from-amber-700 via-amber-600 to-orange-500 text-white py-16 sm:py-20 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="max-w-2xl">
                    <p class="text-amber-200 font-semibold text-sm uppercase tracking-widest mb-3">Free &bull; No sign-up required</p>
                    <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight mb-4">
                        Borrow a book,<br>feed your mind.
                    </h1>
                    <p class="text-amber-100 text-lg leading-relaxed mb-8">
                        Browse our community collection and borrow any book instantly &mdash; no account needed.
                        Just pick, borrow, and read.
                    </p>

                    {{-- Stats --}}
                    <div class="flex flex-wrap gap-6">
                        <div>
                            <p class="text-3xl font-bold">248</p>
                            <p class="text-amber-200 text-sm mt-0.5">Total books</p>
                        </div>
                        <div class="w-px bg-white/20"></div>
                        <div>
                            <p class="text-3xl font-bold text-green-300">183</p>
                            <p class="text-amber-200 text-sm mt-0.5">Available now</p>
                        </div>
                        <div class="w-px bg-white/20"></div>
                        <div>
                            <p class="text-3xl font-bold text-orange-200">65</p>
                            <p class="text-amber-200 text-sm mt-0.5">Currently borrowed</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- HOW IT WORKS --}}
        <section id="how-it-works" class="bg-white border-b border-stone-100 py-10 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-12 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center text-xl font-bold">1</div>
                        <div>
                            <p class="font-semibold text-stone-800">Browse the collection</p>
                            <p class="text-stone-500 text-sm mt-1">Find a book you love from our curated library shelves.</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-12 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center text-xl font-bold">2</div>
                        <div>
                            <p class="font-semibold text-stone-800">Enter your name</p>
                            <p class="text-stone-500 text-sm mt-1">No account needed &mdash; just tell us who is borrowing.</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-12 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center text-xl font-bold">3</div>
                        <div>
                            <p class="font-semibold text-stone-800">Return when done</p>
                            <p class="text-stone-500 text-sm mt-1">Bring it back within 14 days so others can enjoy it too.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- BOOK CATALOGUE --}}
        <section id="books" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            {{-- Header + filters --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-stone-800">Available Books</h2>
                    <p class="text-stone-500 text-sm mt-1">Showing 12 of 248 books</p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <select class="text-sm border border-stone-200 rounded-lg px-3 py-2 bg-white text-stone-700 focus:outline-none focus:ring-2 focus:ring-amber-400">
                        <option>All genres</option>
                        <option>Fiction</option>
                        <option>Non-fiction</option>
                        <option>Science</option>
                        <option>History</option>
                        <option>Fantasy</option>
                        <option>Biography</option>
                    </select>
                    <select class="text-sm border border-stone-200 rounded-lg px-3 py-2 bg-white text-stone-700 focus:outline-none focus:ring-2 focus:ring-amber-400">
                        <option>All statuses</option>
                        <option>Available</option>
                        <option>Borrowed</option>
                    </select>
                </div>
            </div>

            @php
            $books = [
                ['title' => 'The Midnight Library',    'author' => 'Matt Haig',           'genre' => 'Fiction',    'color' => 'bg-indigo-600',  'available' => true,  'year' => 2020],
                ['title' => 'Atomic Habits',           'author' => 'James Clear',          'genre' => 'Self-help',  'color' => 'bg-emerald-600', 'available' => true,  'year' => 2018],
                ['title' => 'Dune',                    'author' => 'Frank Herbert',        'genre' => 'Sci-fi',     'color' => 'bg-amber-700',   'available' => false, 'year' => 1965],
                ['title' => 'Sapiens',                 'author' => 'Yuval N. Harari',      'genre' => 'History',    'color' => 'bg-stone-700',   'available' => true,  'year' => 2011],
                ['title' => 'The Alchemist',           'author' => 'Paulo Coelho',         'genre' => 'Fiction',    'color' => 'bg-orange-500',  'available' => true,  'year' => 1988],
                ['title' => 'Project Hail Mary',       'author' => 'Andy Weir',            'genre' => 'Sci-fi',     'color' => 'bg-sky-700',     'available' => false, 'year' => 2021],
                ['title' => 'Educated',                'author' => 'Tara Westover',        'genre' => 'Biography',  'color' => 'bg-rose-700',    'available' => true,  'year' => 2018],
                ['title' => 'The Name of the Wind',    'author' => 'Patrick Rothfuss',     'genre' => 'Fantasy',    'color' => 'bg-violet-700',  'available' => true,  'year' => 2007],
                ['title' => 'Thinking, Fast and Slow', 'author' => 'Daniel Kahneman',      'genre' => 'Psychology', 'color' => 'bg-teal-700',    'available' => false, 'year' => 2011],
                ['title' => 'To Kill a Mockingbird',   'author' => 'Harper Lee',           'genre' => 'Classic',    'color' => 'bg-lime-700',    'available' => true,  'year' => 1960],
                ['title' => 'The Great Gatsby',        'author' => 'F. Scott Fitzgerald',  'genre' => 'Classic',    'color' => 'bg-yellow-600',  'available' => true,  'year' => 1925],
                ['title' => 'Becoming',                'author' => 'Michelle Obama',       'genre' => 'Biography',  'color' => 'bg-pink-700',    'available' => false, 'year' => 2018],
            ];
            @endphp

            {{-- Book grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-5">
                @foreach ($books as $book)
                <div class="card-hover bg-white rounded-xl overflow-hidden border border-stone-200 flex flex-col shadow-sm">

                    {{-- Cover --}}
                    <div class="relative {{ $book['color'] }} book-spine h-44 flex flex-col items-center justify-center p-4 gap-2">
                        <span class="absolute top-2.5 right-2.5 text-xs font-semibold px-2 py-0.5 rounded-full
                            {{ $book['available'] ? 'bg-green-400/90 text-white' : 'bg-stone-600/80 text-stone-200' }}">
                            {{ $book['available'] ? 'Available' : 'Borrowed' }}
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white/60" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="text-white/70 text-xs font-medium">{{ $book['year'] }}</span>
                    </div>

                    {{-- Info --}}
                    <div class="p-3 flex flex-col flex-1 gap-2">
                        <span class="genre-badge uppercase font-semibold tracking-wider text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded self-start">
                            {{ $book['genre'] }}
                        </span>
                        <div class="flex-1">
                            <h3 class="font-bold text-stone-800 text-sm leading-snug line-clamp-2">{{ $book['title'] }}</h3>
                            <p class="text-stone-500 text-xs mt-1">{{ $book['author'] }}</p>
                        </div>
                        @if ($book['available'])
                            <button
                                onclick="openBorrowModal('{{ addslashes($book['title']) }}', '{{ addslashes($book['author']) }}')"
                                class="w-full mt-1 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold py-2 px-3 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-1"
                            >Borrow</button>
                        @else
                            <button disabled class="w-full mt-1 bg-stone-100 text-stone-400 text-xs font-semibold py-2 px-3 rounded-lg cursor-not-allowed">
                                Unavailable
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Load more --}}
            <div class="flex justify-center mt-10">
                <button class="inline-flex items-center gap-2 border border-stone-300 text-stone-700 hover:border-amber-500 hover:text-amber-700 font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    Load more books
                </button>
            </div>
        </section>

        {{-- FOOTER --}}
        <footer class="border-t border-stone-200 bg-white mt-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-stone-600">
                    <div class="w-7 h-7 bg-amber-600 rounded-md flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="font-semibold">ShelfMate</span>
                </div>
                <p class="text-stone-400 text-sm">Return period: 14 days &bull; Open to all &bull; No account required</p>
                <p class="text-stone-400 text-sm">&copy; {{ date('Y') }} ShelfMate</p>
            </div>
        </footer>

        {{-- BORROW MODAL --}}
        <div id="borrowModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
            {{-- Backdrop --}}
            <div onclick="closeBorrowModal()" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

            {{-- Dialog --}}
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 sm:p-8">
                <button onclick="closeBorrowModal()" class="absolute top-4 right-4 text-stone-400 hover:text-stone-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-stone-800 text-lg leading-tight">Borrow a Book</h3>
                        <p id="modalBookTitle" class="text-stone-500 text-sm mt-0.5"></p>
                    </div>
                </div>
                <form onsubmit="submitBorrow(event)" class="flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-stone-700 mb-1.5">Your full name</label>
                        <input id="borrowerName" type="text" required placeholder="e.g. Alex Rivera"
                            class="w-full border border-stone-200 rounded-lg px-4 py-2.5 text-sm text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                    </div>
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm text-amber-800 flex gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Please return this book within <strong>14 days</strong>. No account required.</span>
                    </div>
                    <div class="flex gap-3 pt-1">
                        <button type="button" onclick="closeBorrowModal()"
                            class="flex-1 border border-stone-200 text-stone-600 font-semibold py-2.5 rounded-lg text-sm hover:bg-stone-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                            Confirm Borrow
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            const modal = document.getElementById('borrowModal');

            function openBorrowModal(title, author) {
                document.getElementById('modalBookTitle').textContent = `"${title}" by ${author}`;
                document.getElementById('borrowerName').value = '';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            function closeBorrowModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }

            function submitBorrow(e) {
                e.preventDefault();
                const name = document.getElementById('borrowerName').value.trim();
                if (!name) return;
                closeBorrowModal();
                showToast(`Borrowed successfully! Enjoy your read, ${name.split(' ')[0]} \uD83D\uDCD6`);
            }

            function showToast(message) {
                const toast = document.createElement('div');
                toast.className =
                    'fixed bottom-6 left-1/2 -translate-x-1/2 bg-stone-800 text-white text-sm font-medium ' +
                    'px-5 py-3 rounded-xl shadow-lg z-50 toast-enter';
                toast.style.whiteSpace = 'nowrap';
                toast.textContent = message;
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translate(-50%, 12px)';
                    setTimeout(() => toast.remove(), 300);
                }, 3500);
            }
        </script>
    </body>
</html>