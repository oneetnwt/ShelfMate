@extends('layouts.public')

@section('title', 'Find My Borrows')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-11 h-11 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-stone-800">Find My Borrows</h1>
                <p class="text-stone-500 text-sm">Enter your name and contact number.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('borrow.lookup') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1.5">Full name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    placeholder="e.g. Maria Santos"
                    class="w-full border @error('name') border-red-400 bg-red-50 @else border-stone-200 bg-stone-50 @enderror
                           rounded-lg px-4 py-2.5 text-sm placeholder-stone-400
                           focus:outline-none focus:ring-2 focus:ring-amber-400">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-stone-700 mb-1.5">Contact number</label>
                <input type="text" name="contact_number" value="{{ old('contact_number') }}"
                    placeholder="09XX-XXX-XXXX"
                    class="w-full border border-stone-200 bg-stone-50 rounded-lg px-4 py-2.5 text-sm
                           placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>

            <button type="submit"
                class="w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 rounded-lg text-sm transition-colors">
                Find My Borrows
            </button>
        </form>

        <p class="text-center text-stone-400 text-xs mt-6">
            Don't have any borrows yet?
            <a href="{{ route('borrow.create') }}" class="text-amber-600 hover:underline">Borrow a book</a>
        </p>
    </div>
</div>
@endsection
