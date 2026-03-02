@extends('layouts.public')

@section('title', $book->title . ' — ShelfMate')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-10">

        {{-- Back link --}}
        <a href="{{ route('books.index') }}"
            class="inline-flex items-center gap-1.5 text-stone-500 hover:text-amber-700 text-sm font-semibold mb-7 group transition-colors">
            <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor"
                stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            Back to Catalog
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
            <div class="md:flex">

                {{-- ── Cover panel ──────────────────────────────────────────────────── --}}
                <div class="md:w-72 shrink-0 bg-stone-100 flex items-center justify-center p-8">
                    @if($book->cover_image)
                        <img src="{{ $book->cover_image }}" alt="{{ $book->title }}"
                            class="rounded-xl shadow-lg object-cover w-48 md:w-full aspect-[2/3]">
                    @else
                        <div class="bg-amber-100 rounded-xl w-48 md:w-full aspect-[2/3] flex items-center justify-center">
                            <svg class="w-16 h-16 text-amber-300" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- ── Book details ──────────────────────────────────────────────────── --}}
                <div class="flex-1 p-8 flex flex-col gap-6">

                    {{-- Title + meta --}}
                    <div>
                        <div class="flex flex-wrap items-start gap-3 mb-3">
                            @if($book->genre)
                                <span
                                    class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded uppercase tracking-wider">
                                    {{ $book->genre }}
                                </span>
                            @endif
                            @php $isAvail = $book->available_copies > 0; @endphp
                            <span
                                class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $isAvail ? 'bg-green-100 text-green-700' : 'bg-stone-100 text-stone-500' }}">
                                {{ $isAvail ? 'Available' : 'Unavailable' }}
                            </span>
                        </div>

                        <h1 class="text-2xl font-extrabold text-stone-800 leading-snug">{{ $book->title }}</h1>

                        @if($book->authors->isNotEmpty())
                            <p class="text-stone-500 mt-1">
                                by
                                <span class="font-semibold text-stone-700">{{ $book->authorNames() }}</span>
                            </p>
                        @endif
                    </div>

                    {{-- Key facts grid --}}
                    <dl class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                        <div>
                            <dt class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-0.5">Copies</dt>
                            <dd class="font-bold text-stone-800">
                                {{ $book->available_copies }}
                                <span class="font-normal text-stone-400">/ {{ $book->total_copies }}</span>
                                <span class="ml-1 text-xs text-stone-400">available</span>
                            </dd>
                        </div>
                        @if($book->published_year)
                            <div>
                                <dt class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-0.5">Published</dt>
                                <dd class="font-bold text-stone-800">{{ $book->published_year }}</dd>
                            </div>
                        @endif
                        @if($book->isbn)
                            <div>
                                <dt class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-0.5">ISBN</dt>
                                <dd class="font-mono text-stone-700">{{ $book->isbn }}</dd>
                            </div>
                        @endif
                    </dl>

                    {{-- Description --}}
                    @if($book->description)
                        <div>
                            <h2 class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-1.5">About this Book
                            </h2>
                            <p class="text-stone-600 text-sm leading-relaxed">{{ $book->description }}</p>
                        </div>
                    @endif

                    {{-- Authors detail --}}
                    @if($book->authors->isNotEmpty())
                        <div>
                            <h2 class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-2">
                                Author{{ $book->authors->count() > 1 ? 's' : '' }}</h2>
                            <div class="flex flex-wrap gap-2">
                                @foreach($book->authors as $author)
                                    <div class="flex items-center gap-2 bg-stone-50 border border-stone-200 rounded-xl px-3 py-2">
                                        <div
                                            class="w-7 h-7 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($author->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-stone-800 text-sm">{{ $author->name }}</p>
                                            @if($author->bio)
                                                <p class="text-stone-400 text-xs line-clamp-1">{{ Str::limit($author->bio, 60) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- CTA --}}
                    <div class="pt-2">
                        @if($isAvail)
                            <a href="{{ route('borrow.create', ['books[]' => $book->id]) }}"
                                class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors shadow text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Borrow This Book
                            </a>
                        @else
                            <button disabled
                                class="inline-flex items-center gap-2 bg-stone-200 text-stone-400 font-semibold px-6 py-3 rounded-xl text-sm cursor-not-allowed">
                                All Copies Borrowed
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── Active borrows section ────────────────────────────────────────── --}}
            @if($activeBorrows->isNotEmpty())
                <div class="border-t border-stone-100 px-8 py-6">
                    <h2 class="font-bold text-stone-700 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                        </svg>
                        Currently Borrowed
                        <span
                            class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-semibold">{{ $activeBorrows->count() }}</span>
                    </h2>
                    <div class="space-y-2">
                        @foreach($activeBorrows as $record)
                            @php $isOverdue = $record->isOverdue(); @endphp
                            <div
                                class="flex items-center justify-between text-sm bg-stone-50 rounded-xl px-4 py-3 border border-stone-100">
                                <div>
                                    <span class="font-semibold text-stone-700">{{ $record->borrower->name }}</span>
                                    <span class="text-stone-400 text-xs ml-2">borrowed
                                        {{ $record->borrow_date->format('M j, Y') }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs {{ $isOverdue ? 'text-red-600 font-bold' : 'text-stone-500' }}">
                                        Due {{ $record->due_date->format('M j, Y') }}
                                    </span>
                                    @if($isOverdue)
                                        <span class="ml-2 text-xs bg-red-100 text-red-700 font-semibold px-2 py-0.5 rounded-full">
                                            Overdue {{ $record->daysOverdue() }}d
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── Recent returns ────────────────────────────────────────────────── --}}
            @if($recentReturns->isNotEmpty())
                <div class="border-t border-stone-100 px-8 py-6">
                    <h2 class="font-bold text-stone-700 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Recent Returns
                    </h2>
                    <div class="space-y-2">
                        @foreach($recentReturns as $record)
                            <div
                                class="flex items-center justify-between text-sm bg-stone-50 rounded-xl px-4 py-3 border border-stone-100">
                                <span class="font-semibold text-stone-600">{{ $record->borrower->name }}</span>
                                <span class="text-stone-400 text-xs">returned {{ $record->return_date->format('M j, Y') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection