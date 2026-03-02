@extends('layouts.public')

@section('title', 'Borrow Books')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-stone-500 mb-6 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-amber-700 transition-colors">Browse</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-stone-800 font-medium">Borrow Books</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

        {{-- Left: Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6 sticky top-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-stone-800">Your Information</h2>
                        <p class="text-stone-500 text-xs">No account needed</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('borrow.store') }}" id="borrowForm">
                    @csrf

                    {{-- Hidden book_ids populated by JS --}}
                    <div id="hiddenBookIds"></div>

                    {{-- Name --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-stone-700 mb-1.5">
                            Full name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="e.g. Maria Santos"
                            class="w-full border @error('name') border-red-400 bg-red-50 @else border-stone-200 bg-stone-50 @enderror
                                   rounded-lg px-4 py-2.5 text-sm text-stone-800 placeholder-stone-400
                                   focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Contact --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-stone-700 mb-1.5">Contact number</label>
                        <input type="text" name="contact_number" value="{{ old('contact_number') }}"
                            placeholder="e.g. 09XX-XXX-XXXX"
                            class="w-full border border-stone-200 bg-stone-50 rounded-lg px-4 py-2.5 text-sm
                                   text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2
                                   focus:ring-amber-400 focus:border-transparent">
                        <p class="text-stone-400 text-xs mt-1">Used to look up your borrows later.</p>
                    </div>

                    {{-- Email --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-stone-700 mb-1.5">Email <span class="text-stone-400 font-normal">(optional)</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="you@example.com"
                            class="w-full border border-stone-200 bg-stone-50 rounded-lg px-4 py-2.5 text-sm
                                   text-stone-800 placeholder-stone-400 focus:outline-none focus:ring-2
                                   focus:ring-amber-400 focus:border-transparent">
                    </div>

                    {{-- Fine notice --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm text-amber-800 flex gap-2 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Return within <strong>14 days</strong>. A fine of <strong>&#8369;10 per day</strong> per book applies if overdue.</span>
                    </div>

                    @error('book_ids')
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 mb-4">
                            {{ $message }}
                        </div>
                    @enderror

                    <button type="submit" id="submitBtn" disabled
                        class="w-full bg-amber-600 hover:bg-amber-700 disabled:bg-stone-200 disabled:text-stone-400
                               disabled:cursor-not-allowed text-white font-semibold py-3 px-4 rounded-lg
                               transition-colors text-sm">
                        Confirm Borrow (<span id="selectedCount">0</span> book<span id="bookPlural">s</span> selected)
                    </button>
                </form>
            </div>
        </div>

        {{-- Right: Book picker --}}
        <div class="lg:col-span-3">
            <h2 class="text-xl font-bold text-stone-800 mb-1">Select Books to Borrow</h2>
            <p class="text-stone-500 text-sm mb-5">
                {{ $availableBooks->count() }} book(s) available &mdash; select one or more.
            </p>

            @if ($availableBooks->isEmpty())
                <div class="text-center py-16 bg-white rounded-2xl border border-stone-200">
                    <p class="text-stone-500 font-semibold">No books are currently available.</p>
                    <a href="{{ route('home') }}" class="text-amber-600 text-sm underline mt-2 block">Back to catalogue</a>
                </div>
            @else
                <div class="space-y-3" id="bookList">
                    @php
                        $preIds = array_map('intval', (array) request()->query('books', []));
                        $coverColors = ['bg-indigo-600','bg-emerald-600','bg-amber-700','bg-stone-700','bg-orange-500','bg-sky-700','bg-rose-700','bg-violet-700','bg-teal-700','bg-lime-700','bg-yellow-600','bg-pink-700'];
                    @endphp

                    @foreach ($availableBooks as $i => $book)
                        @php
                            $color      = $coverColors[$i % count($coverColors)];
                            $authorName = $book->authorNames();
                            $isPresel   = in_array($book->id, $preIds);
                        @endphp
                        <label for="book_{{ $book->id }}"
                            class="flex items-center gap-4 p-4 bg-white rounded-xl border-2 cursor-pointer transition-all
                                   {{ $isPresel ? 'border-amber-500 bg-amber-50' : 'border-stone-200 hover:border-amber-300' }}"
                            data-book-id="{{ $book->id }}">

                            {{-- Checkbox (hidden, styled via label) --}}
                            <input type="checkbox" id="book_{{ $book->id }}" class="book-checkbox sr-only"
                                data-id="{{ $book->id }}" {{ $isPresel ? 'checked' : '' }}>

                            {{-- Cover thumbnail --}}
                            <div class="{{ $color }} rounded-lg w-14 h-20 shrink-0 overflow-hidden">
                                @if ($book->cover_image)
                                    <img src="{{ $book->cover_image }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white/60" fill="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-stone-800 text-sm leading-snug line-clamp-2">{{ $book->title }}</p>
                                <p class="text-stone-500 text-xs mt-0.5">{{ $authorName }}</p>
                                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                    @if ($book->genre)
                                        <span class="text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded uppercase tracking-wide" style="font-size:0.65rem">{{ $book->genre }}</span>
                                    @endif
                                    <span class="text-xs text-green-700 bg-green-50 border border-green-200 px-1.5 py-0.5 rounded">
                                        {{ $book->available_copies }}/{{ $book->total_copies }} available
                                    </span>
                                </div>
                            </div>

                            {{-- Check indicator --}}
                            <div class="check-indicator w-6 h-6 rounded-full border-2 shrink-0 flex items-center justify-center transition-colors
                                        {{ $isPresel ? 'bg-amber-600 border-amber-600' : 'border-stone-300' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white {{ $isPresel ? '' : 'hidden' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const checkboxes   = document.querySelectorAll('.book-checkbox');
    const hiddenDiv    = document.getElementById('hiddenBookIds');
    const submitBtn    = document.getElementById('submitBtn');
    const countSpan    = document.getElementById('selectedCount');
    const pluralSpan   = document.getElementById('bookPlural');

    function updateUI() {
        const selected = [...checkboxes].filter(c => c.checked);
        const count    = selected.length;

        // Update hidden inputs
        hiddenDiv.innerHTML = '';
        selected.forEach(c => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'book_ids[]';
            inp.value = c.dataset.id;
            hiddenDiv.appendChild(inp);
        });

        // Update button
        countSpan.textContent = count;
        pluralSpan.textContent = count === 1 ? '' : 's';
        submitBtn.disabled = count === 0;

        // Update label styles
        checkboxes.forEach(c => {
            const label  = document.querySelector(`label[for="${c.id}"]`);
            const circle = label.querySelector('.check-indicator');
            const check  = circle.querySelector('svg');
            if (c.checked) {
                label.classList.replace('border-stone-200', 'border-amber-500');
                label.classList.add('bg-amber-50');
                circle.classList.add('bg-amber-600', 'border-amber-600');
                circle.classList.remove('border-stone-300');
                check.classList.remove('hidden');
            } else {
                label.classList.replace('border-amber-500', 'border-stone-200');
                label.classList.remove('bg-amber-50');
                circle.classList.remove('bg-amber-600', 'border-amber-600');
                circle.classList.add('border-stone-300');
                check.classList.add('hidden');
            }
        });
    }

    checkboxes.forEach(c => c.addEventListener('change', updateUI));
    updateUI(); // run on load for pre-selected
</script>
@endpush
