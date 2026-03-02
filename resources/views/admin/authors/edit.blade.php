@extends('layouts.admin')

@section('title', 'Edit Author')
@section('heading', 'Edit Author')

@section('actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.authors.show', $author) }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-stone-600 hover:text-stone-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Back
        </a>
        <span class="text-stone-200 select-none">|</span>
        <form method="POST" action="{{ route('admin.authors.destroy', $author) }}"
              onsubmit="return confirm('Permanently delete {{ addslashes($author->name) }}?')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-red-500 hover:text-red-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                </svg>
                Delete Author
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="grid lg:grid-cols-3 gap-6 items-start">

    {{-- Edit form --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-stone-200 shadow-sm">
        <div class="px-8 py-6 border-b border-stone-100">
            <h2 class="font-semibold text-stone-800">Author Details</h2>
        </div>

        <form method="POST" action="{{ route('admin.authors.update', $author) }}" class="px-8 py-6 space-y-5">
            @csrf @method('PUT')

            <div>
                <label for="name" class="block text-sm font-semibold text-stone-700 mb-1.5">Name <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name', $author->name) }}" required autofocus
                       class="w-full text-sm border border-stone-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="bio" class="block text-sm font-semibold text-stone-700 mb-1.5">Bio</label>
                <textarea id="bio" name="bio" rows="6"
                          class="w-full text-sm border border-stone-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent resize-y @error('bio') border-red-400 @enderror">{{ old('bio', $author->bio) }}</textarea>
                @error('bio')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.authors.index') }}"
                   class="text-sm font-semibold text-stone-500 hover:text-stone-700 px-4 py-2.5 rounded-xl border border-stone-200 hover:border-stone-300 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="text-sm font-bold bg-amber-500 hover:bg-amber-400 text-white px-6 py-2.5 rounded-xl transition-colors shadow-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Associated books panel --}}
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-stone-100 flex items-center justify-between">
            <h3 class="font-semibold text-stone-800 text-sm">Books</h3>
            <span class="text-xs font-bold bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">
                {{ $author->books->count() }}
            </span>
        </div>
        @if($author->books->isEmpty())
            <p class="px-6 py-8 text-sm text-stone-400 text-center">No books assigned.</p>
        @else
            <ul class="divide-y divide-stone-50">
                @foreach($author->books as $book)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <div class="w-7 h-10 rounded bg-stone-100 overflow-hidden shrink-0">
                            @if($book->cover_image)
                                <img src="{{ $book->cover_image }}" class="w-full h-full object-cover" alt="">
                            @endif
                        </div>
                        <p class="text-sm text-stone-700 font-medium leading-snug">{{ $book->title }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</div>
@endsection