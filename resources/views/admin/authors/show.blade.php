@extends('layouts.admin')

@section('title', $author->name)
@section('heading', $author->name)

@section('actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.authors.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-stone-600 hover:text-stone-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            All Authors
        </a>
        <a href="{{ route('admin.authors.edit', $author) }}"
           class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-white text-sm font-bold px-4 py-2 rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
            </svg>
            Edit
        </a>
    </div>
@endsection

@section('content')
<div class="grid lg:grid-cols-3 gap-6 items-start">

    {{-- Author profile card --}}
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-8">
        <div class="flex flex-col items-center text-center">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white font-extrabold text-3xl mb-4 shadow">
                {{ strtoupper(substr($author->name, 0, 1)) }}
            </div>
            <h2 class="text-xl font-extrabold text-stone-800">{{ $author->name }}</h2>
            <span class="mt-2 text-xs font-bold bg-amber-100 text-amber-700 px-3 py-1 rounded-full">
                {{ $author->books->count() }} {{ Str::plural('Book', $author->books->count()) }}
            </span>
        </div>

        @if($author->bio)
            <div class="mt-6 pt-6 border-t border-stone-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-stone-400 mb-2">Biography</h3>
                <p class="text-sm text-stone-600 leading-relaxed">{{ $author->bio }}</p>
            </div>
        @endif

        <div class="mt-6 pt-4 border-t border-stone-100 space-y-2">
            <div class="text-xs text-stone-400 flex justify-between">
                <span class="font-semibold">Added</span>
                <span>{{ $author->created_at->format('M j, Y') }}</span>
            </div>
            <div class="text-xs text-stone-400 flex justify-between">
                <span class="font-semibold">Updated</span>
                <span>{{ $author->updated_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>

    {{-- Books grid --}}
    <div class="lg:col-span-2">
        <h3 class="text-sm font-bold uppercase tracking-wider text-stone-400 mb-4 px-1">Books by {{ $author->name }}</h3>

        @if($author->books->isEmpty())
            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm py-20 text-center">
                <p class="text-stone-400 text-sm">No books assigned to this author yet.</p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($author->books as $book)
                    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden hover:shadow-md hover:border-amber-200 transition-all group">
                        <div class="h-40 bg-stone-100 overflow-hidden">
                            @if($book->cover_image)
                                <img src="{{ $book->cover_image }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     alt="{{ $book->title }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-stone-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <p class="text-sm font-bold text-stone-800 leading-snug">{{ $book->title }}</p>
                            <div class="mt-2 flex items-center justify-between text-xs text-stone-400">
                                <span>{{ $book->available_copies }}/{{ $book->total_copies }} available</span>
                                @if($book->available_copies > 0)
                                    <span class="bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full">Available</span>
                                @else
                                    <span class="bg-red-100 text-red-600 font-bold px-2 py-0.5 rounded-full">Checked out</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection