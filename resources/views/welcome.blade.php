@extends('layouts.public')

@section('title', 'Browse Books')

@section('content')

    {{-- HERO --}}
    <section class="bg-gradient-to-br from-amber-700 via-amber-600 to-orange-500 text-white py-16 sm:py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="max-w-2xl">
                <p class="text-amber-200 font-semibold text-sm uppercase tracking-widest mb-3">Free &bull; No sign-up
                    required</p>
                <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight mb-4">Borrow a book,<br>feed your mind.</h1>
                <p class="text-amber-100 text-lg leading-relaxed mb-8">
                    Browse our community collection and borrow any book instantly — no account needed. Just pick, borrow,
                    and return within 14 days.
                </p>
                <div class="flex flex-wrap gap-6">
                    <div>
                        <p class="text-3xl font-bold">{{ $totalBooks }}</p>
                        <p class="text-amber-200 text-sm mt-0.5">Total books</p>
                    </div>
                    <div class="w-px bg-white/20"></div>
                    <div>
                        <p class="text-3xl font-bold text-green-300">{{ $available }}</p>
                        <p class="text-amber-200 text-sm mt-0.5">Available now</p>
                    </div>
                    <div class="w-px bg-white/20"></div>
                    <div>
                        <p class="text-3xl font-bold text-orange-200">{{ $borrowed }}</p>
                        <p class="text-amber-200 text-sm mt-0.5">Currently borrowed</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section class="bg-white border-b border-stone-100 py-10 px-4">
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
            @foreach ([['1', 'Browse the collection', 'Find a book you love from our curated shelves.'], ['2', 'Enter your name', 'No account needed — just tell us who is borrowing.'], ['3', 'Return when done', 'Bring it back within 14 days. ₱10/day fine if overdue.']] as [$n, $title, $desc])
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="w-12 h-12 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center text-xl font-bold">
                        {{ $n }}</div>
                    <div>
                        <p class="font-semibold text-stone-800">{{ $title }}</p>
                        <p class="text-stone-500 text-sm mt-1">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- CATALOGUE --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Search & Filters --}}
        <form method="GET" action="{{ route('home') }}" class="flex flex-wrap gap-3 items-center mb-8">
            <div class="relative flex-1 min-w-52">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-stone-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by title, author, or genre…"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-stone-200 bg-white text-sm placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>
            <select name="genre"
                class="text-sm border border-stone-200 rounded-lg px-3 py-2.5 bg-white text-stone-700 focus:outline-none focus:ring-2 focus:ring-amber-400">
                <option value="">All genres</option>
                @foreach ($genres as $g)
                    <option value="{{ $g }}" @selected(request('genre') === $g)>{{ $g }}</option>
                @endforeach
            </select>
            <select name="status"
                class="text-sm border border-stone-200 rounded-lg px-3 py-2.5 bg-white text-stone-700 focus:outline-none focus:ring-2 focus:ring-amber-400">
                <option value="">All statuses</option>
                <option value="available" @selected(request('status') === 'available')>Available</option>
                <option value="borrowed" @selected(request('status') === 'borrowed')>Borrowed</option>
            </select>
            <button type="submit"
                class="px-4 py-2.5 bg-stone-800 hover:bg-stone-700 text-white text-sm font-semibold rounded-lg transition-colors">Search</button>
            @if(request()->anyFilled(['q', 'genre', 'status']))
                <a href="{{ route('home') }}"
                    class="px-4 py-2.5 border border-stone-300 text-stone-600 text-sm font-semibold rounded-lg hover:border-stone-400 transition-colors">Clear</a>
            @endif
        </form>

        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-2xl font-bold text-stone-800">Book Catalogue</h2>
                <p class="text-stone-500 text-sm mt-0.5">Showing {{ $books->firstItem() }}–{{ $books->lastItem() }} of
                    {{ $books->total() }} books</p>
            </div>
            <a href="{{ route('borrow.create') }}"
                class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Borrow Books
            </a>
        </div>

        @php
            $coverColors = ['bg-indigo-600', 'bg-emerald-600', 'bg-amber-700', 'bg-stone-700', 'bg-orange-500', 'bg-sky-700', 'bg-rose-700', 'bg-violet-700', 'bg-teal-700', 'bg-lime-700', 'bg-yellow-600', 'bg-pink-700'];
        @endphp

        @if($books->isEmpty())
            <div class="text-center py-24">
                <p class="text-lg font-semibold text-stone-500">No books found.</p>
                <p class="text-sm text-stone-400 mt-1">Try a different search or <a href="{{ route('home') }}"
                        class="text-amber-600 underline">clear filters</a>.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-5">
                @foreach($books as $book)
                    @php
                        $color = $coverColors[$loop->index % count($coverColors)];
                        $isAvail = $book->available_copies > 0;
                        $authorName = $book->authorNames();
                    @endphp
                    <div class="card-hover bg-white rounded-xl overflow-hidden border border-stone-200 flex flex-col shadow-sm">
                        <div class="relative {{ $color }} book-spine h-44 overflow-hidden">
                            @if($book->cover_image)
                                <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center p-4 gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white/60" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <span class="text-white/70 text-xs">{{ $book->published_year }}</span>
                                </div>
                            @endif
                            <span
                                class="absolute top-2 right-2 text-xs font-semibold px-2 py-0.5 rounded-full {{ $isAvail ? 'bg-green-400/90 text-white' : 'bg-stone-600/80 text-stone-200' }}">
                                {{ $isAvail ? 'Available' : 'Borrowed' }}
                            </span>
                        </div>
                        <div class="p-3 flex flex-col flex-1 gap-2">
                            @if($book->genre)
                                <span
                                    class="genre-badge uppercase font-semibold tracking-wider text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded self-start">{{ $book->genre }}</span>
                            @endif
                            <div class="flex-1">
                                <h3 class="font-bold text-stone-800 text-sm leading-snug line-clamp-2">{{ $book->title }}</h3>
                                <p class="text-stone-500 text-xs mt-0.5">{{ $authorName }}</p>
                                <p class="text-stone-400 text-xs">{{ $book->published_year }}</p>
                            </div>
                            @if($isAvail)
                                <a href="{{ route('borrow.create', ['books[]' => $book->id]) }}"
                                    class="w-full text-center mt-1 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold py-2 px-3 rounded-lg transition-colors">
                                    Borrow
                                </a>
                            @else
                                <span
                                    class="w-full text-center mt-1 bg-stone-100 text-stone-400 text-xs font-semibold py-2 px-3 rounded-lg block cursor-not-allowed">
                                    Unavailable
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($books->hasPages())
                <div class="flex justify-center mt-10 gap-2 flex-wrap text-sm">
                    @if($books->onFirstPage())
                        <span class="px-4 py-2 rounded-lg border border-stone-200 text-stone-300 cursor-not-allowed">&larr; Prev</span>
                    @else
                        <a href="{{ $books->previousPageUrl() }}"
                            class="px-4 py-2 rounded-lg border border-stone-300 text-stone-700 hover:border-amber-500 hover:text-amber-700 font-semibold transition-colors">&larr;
                            Prev</a>
                    @endif
                    @foreach($books->getUrlRange(1, $books->lastPage()) as $page => $url)
                        @if($page === $books->currentPage())
                            <span class="px-4 py-2 rounded-lg bg-amber-600 text-white font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-4 py-2 rounded-lg border border-stone-300 text-stone-700 hover:border-amber-500 hover:text-amber-700 font-semibold transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if($books->hasMorePages())
                        <a href="{{ $books->nextPageUrl() }}"
                            class="px-4 py-2 rounded-lg border border-stone-300 text-stone-700 hover:border-amber-500 hover:text-amber-700 font-semibold transition-colors">Next
                            &rarr;</a>
                    @else
                        <span class="px-4 py-2 rounded-lg border border-stone-200 text-stone-300 cursor-not-allowed">Next &rarr;</span>
                    @endif
                </div>
            @endif
        @endif
    </section>

@endsection