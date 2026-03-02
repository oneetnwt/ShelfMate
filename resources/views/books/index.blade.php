@extends('layouts.public')

@section('title', 'Book Catalog — ShelfMate')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-10">

        {{-- ── Page header ──────────────────────────────────────────────────────── --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-stone-800">Book Catalog</h1>
                <p class="text-stone-500 mt-1">
                    {{ number_format($total) }} book{{ $total !== 1 ? 's' : '' }} in the library &mdash;
                    <span class="text-green-700 font-semibold">{{ number_format($avail) }} available</span>
                </p>
            </div>
            <a href="{{ route('borrow.create') }}"
                class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold px-5 py-2.5 rounded-xl transition-colors text-sm shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Borrow a Book
            </a>
        </div>

        {{-- ── Filters ──────────────────────────────────────────────────────────── --}}
        <form method="GET" action="{{ route('books.index') }}" class="mb-8 flex flex-col sm:flex-row gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search title, ISBN, author, or genre…"
                class="flex-1 border border-stone-200 rounded-xl px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">

            <select name="genre"
                class="border border-stone-200 rounded-xl px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                <option value="">All genres</option>
                @foreach($genres as $g)
                    <option value="{{ $g }}" {{ request('genre') === $g ? 'selected' : '' }}>{{ $g }}</option>
                @endforeach
            </select>

            <select name="status"
                class="border border-stone-200 rounded-xl px-4 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                <option value="">All statuses</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
            </select>

            <button type="submit"
                class="bg-amber-600 hover:bg-amber-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-colors shadow">
                Search
            </button>

            @if(request()->hasAny(['q', 'genre', 'status']))
                <a href="{{ route('books.index') }}"
                    class="inline-flex items-center gap-1 text-stone-500 hover:text-stone-700 px-3 py-2.5 rounded-xl text-sm border border-stone-200 bg-white shadow-sm">
                    Clear
                </a>
            @endif
        </form>

        {{-- ── Results ──────────────────────────────────────────────────────────── --}}
        @if($books->isEmpty())
            <div class="text-center py-20 text-stone-400">
                <svg class="w-14 h-14 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <p class="text-lg font-semibold">No books found.</p>
                <p class="text-sm">Try adjusting your search or filters.</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-2xl shadow-sm border border-stone-200 bg-white">
                <table class="w-full text-sm text-left">
                    <thead class="bg-stone-50 text-stone-500 uppercase text-xs tracking-wider border-b border-stone-200">
                        <tr>
                            <th class="px-5 py-3.5 font-semibold">Book</th>
                            <th class="px-5 py-3.5 font-semibold hidden sm:table-cell">Author(s)</th>
                            <th class="px-5 py-3.5 font-semibold hidden md:table-cell">Genre</th>
                            <th class="px-5 py-3.5 font-semibold hidden lg:table-cell">ISBN</th>
                            <th class="px-5 py-3.5 font-semibold text-center">Inventory</th>
                            <th class="px-5 py-3.5 font-semibold text-center">Status</th>
                            <th class="px-3 py-3.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach($books as $book)
                            @php $isAvail = $book->available_copies > 0; @endphp
                            <tr class="hover:bg-amber-50/40 transition-colors">
                                {{-- Cover + title --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($book->cover_image)
                                            <img src="{{ $book->cover_image }}" alt="{{ $book->title }}"
                                                class="w-10 h-13 rounded object-cover shadow-sm shrink-0" style="height:3.25rem">
                                        @else
                                            <div class="w-10 rounded bg-amber-100 flex items-center justify-center shrink-0"
                                                style="height:3.25rem">
                                                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <a href="{{ route('books.show', $book) }}"
                                                class="font-semibold text-stone-800 hover:text-amber-700 line-clamp-2 leading-snug">
                                                {{ $book->title }}
                                            </a>
                                            @if($book->published_year)
                                                <p class="text-stone-400 text-xs mt-0.5">{{ $book->published_year }}</p>
                                            @endif
                                            {{-- Authors visible on mobile only --}}
                                            <p class="text-stone-500 text-xs mt-0.5 sm:hidden">{{ $book->authorNames() }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Authors --}}
                                <td class="px-5 py-4 hidden sm:table-cell text-stone-600">{{ $book->authorNames() }}</td>

                                {{-- Genre --}}
                                <td class="px-5 py-4 hidden md:table-cell">
                                    @if($book->genre)
                                        <span
                                            class="text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded uppercase tracking-wide">
                                            {{ $book->genre }}
                                        </span>
                                    @else
                                        <span class="text-stone-300">—</span>
                                    @endif
                                </td>

                                {{-- ISBN --}}
                                <td class="px-5 py-4 hidden lg:table-cell text-stone-400 font-mono text-xs">
                                    {{ $book->isbn ?? '—' }}
                                </td>

                                {{-- Inventory --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="font-bold text-stone-700">{{ $book->available_copies }}</span>
                                    <span class="text-stone-400">/</span>
                                    <span class="text-stone-500">{{ $book->total_copies }}</span>
                                </td>

                                {{-- Status pill --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                                {{ $isAvail ? 'bg-green-100 text-green-700' : 'bg-stone-100 text-stone-500' }}">
                                        {{ $isAvail ? 'Available' : 'Unavailable' }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-3 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('books.show', $book) }}"
                                            class="text-xs text-amber-700 hover:text-amber-900 font-semibold hover:underline whitespace-nowrap">
                                            View
                                        </a>
                                        @if($isAvail)
                                            <a href="{{ route('borrow.create', ['books[]' => $book->id]) }}"
                                                class="text-xs bg-amber-600 hover:bg-amber-700 text-white font-semibold px-2.5 py-1 rounded-lg transition-colors whitespace-nowrap">
                                                Borrow
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($books->hasPages())
                <div class="mt-8">
                    {{ $books->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection