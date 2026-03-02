@extends('layouts.admin')

@section('title', 'New Author')
@section('heading', 'New Author')

@section('actions')
    <a href="{{ route('admin.authors.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-stone-600 hover:text-stone-900 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
        </svg>
        Back to Authors
    </a>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm">
        <div class="px-8 py-6 border-b border-stone-100">
            <h2 class="font-semibold text-stone-800">Author Details</h2>
            <p class="text-sm text-stone-400 mt-0.5">Fill in the fields below to add a new author.</p>
        </div>

        <form method="POST" action="{{ route('admin.authors.store') }}" class="px-8 py-6 space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-semibold text-stone-700 mb-1.5">Name <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                       class="w-full text-sm border border-stone-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="bio" class="block text-sm font-semibold text-stone-700 mb-1.5">Bio</label>
                <textarea id="bio" name="bio" rows="5"
                          class="w-full text-sm border border-stone-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent resize-y @error('bio') border-red-400 @enderror">{{ old('bio') }}</textarea>
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
                    Create Author
                </button>
            </div>
        </form>
    </div>
</div>
@endsection