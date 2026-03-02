@extends('layouts.public')

@section('title', 'My Borrows')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-stone-800">My Borrows</h1>
            <p class="text-stone-500 text-sm mt-0.5">Borrower: <strong class="text-stone-700">{{ $borrower->name }}</strong>
                @if ($borrower->contact_number)
                    &bull; {{ $borrower->contact_number }}
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('borrow.create') }}"
                class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Borrow More
            </a>
            <form method="POST" action="{{ route('borrow.clear') }}">
                @csrf
                <button type="submit" class="text-sm text-stone-500 hover:text-stone-700 underline">Not me?</button>
            </form>
        </div>
    </div>

    {{-- Active Borrows --}}
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm mb-8">
        <div class="flex items-center justify-between px-6 py-4 border-b border-stone-100">
            <h2 class="font-bold text-stone-800 text-lg">
                Active Borrows
                <span class="ml-2 text-sm font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">
                    {{ $activeRecords->count() }}
                </span>
            </h2>
            @if ($totalFinePreview > 0)
                <span class="text-sm font-semibold text-red-600 bg-red-50 border border-red-200 px-3 py-1 rounded-full">
                    Accrued fine: &#8369;{{ number_format($totalFinePreview, 2) }}
                </span>
            @endif
        </div>

        @if ($activeRecords->isEmpty())
            <div class="px-6 py-12 text-center text-stone-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-stone-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="font-semibold">No active borrows.</p>
                <a href="{{ route('borrow.create') }}" class="text-amber-600 text-sm underline mt-1 block">Borrow a book</a>
            </div>
        @else
            <form method="POST" action="{{ route('borrow.return') }}" id="returnForm">
                @csrf

                @error('record_ids')
                    <div class="mx-6 mt-4 bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700">
                        {{ $message }}
                    </div>
                @enderror

                <div class="divide-y divide-stone-100">
                    @foreach ($activeRecords as $record)
                        @php
                            $overdue = $record->days_overdue > 0;
                            $fine    = $record->fine_preview;
                        @endphp
                        <label for="rec_{{ $record->id }}"
                            class="flex items-start gap-4 px-6 py-4 cursor-pointer hover:bg-stone-50 transition-colors group"
                            data-days="{{ $record->days_overdue }}"
                            data-fine="{{ $fine }}">

                            {{-- Checkbox --}}
                            <input type="checkbox" name="record_ids[]" id="rec_{{ $record->id }}"
                                value="{{ $record->id }}" class="return-checkbox mt-1 w-4 h-4 accent-amber-600 cursor-pointer">

                            {{-- Cover thumb --}}
                            <div class="w-10 h-14 rounded-lg overflow-hidden shrink-0 bg-stone-200">
                                @if ($record->book->cover_image)
                                    <img src="{{ $record->book->cover_image }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-amber-700 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/60" fill="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Book info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-stone-800 text-sm leading-snug">{{ $record->book->title }}</p>
                                <p class="text-stone-500 text-xs mt-0.5">{{ $record->book->authorNames() }}</p>
                                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-stone-500">
                                    <span>Borrowed: {{ $record->borrow_date->format('M j, Y') }}</span>
                                    <span class="{{ $overdue ? 'text-red-600 font-semibold' : 'text-stone-500' }}">
                                        Due: {{ $record->due_date->format('M j, Y') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Status & fine --}}
                            <div class="text-right shrink-0">
                                @if ($overdue)
                                    <span class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        Overdue {{ $record->days_overdue }}d
                                    </span>
                                    <p class="text-red-600 font-bold text-sm mt-1">&#8369;{{ number_format($fine, 2) }}</p>
                                    <p class="text-red-400 text-xs mt-0.5">&#8369;10 &times; {{ $record->days_overdue }}d</p>
                                @else
                                    @php $daysLeft = today()->diffInDays($record->due_date, false); @endphp
                                    <span class="inline-block {{ $daysLeft <= 2 ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }} text-xs font-semibold px-2.5 py-1 rounded-full">
                                        {{ $daysLeft }}d left
                                    </span>
                                    <p class="text-stone-400 text-xs mt-1">No fine</p>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>

                {{-- Return actions --}}
                <div class="px-6 py-4 border-t border-stone-100 bg-stone-50 rounded-b-2xl">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div class="text-sm text-stone-500" id="returnSummary">Select books above to return them.</div>
                        <div class="flex gap-3">
                            <button type="button" id="selectAllBtn"
                                class="text-sm border border-stone-300 text-stone-600 hover:border-amber-500 hover:text-amber-700 font-semibold px-4 py-2 rounded-lg transition-colors">
                                Select all
                            </button>
                            <button type="submit" id="returnBtn" disabled
                                class="text-sm bg-red-600 hover:bg-red-700 disabled:bg-stone-200 disabled:text-stone-400
                                       disabled:cursor-not-allowed text-white font-semibold px-5 py-2 rounded-lg transition-colors">
                                Return selected
                            </button>
                        </div>
                    </div>
                    {{-- Live fine breakdown --}}
                    <div id="fineBreakdown" class="hidden mt-3 text-xs text-red-600 font-semibold bg-red-50 border border-red-200 rounded-lg px-4 py-2">
                    </div>
                </div>
            </form>
        @endif
    </div>

    {{-- Return History --}}
    @if ($returnedRecords->isNotEmpty())
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm">
            <div class="px-6 py-4 border-b border-stone-100">
                <h2 class="font-bold text-stone-800 text-lg">Return History</h2>
            </div>
            <div class="divide-y divide-stone-100">
                @foreach ($returnedRecords as $record)
                    <div class="flex items-center gap-4 px-6 py-4">
                        <div class="w-8 h-11 rounded overflow-hidden shrink-0 bg-stone-200">
                            @if ($record->book->cover_image)
                                <img src="{{ $record->book->cover_image }}" alt="" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-stone-800 text-sm line-clamp-1">{{ $record->book->title }}</p>
                            <p class="text-stone-400 text-xs mt-0.5">
                                Borrowed {{ $record->borrow_date->format('M j, Y') }}
                                &bull; Due {{ $record->due_date->format('M j, Y') }}
                                &bull; Returned {{ $record->return_date?->format('M j, Y') }}
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            @if ($record->fine_amount > 0)
                                <span class="text-xs font-semibold text-red-600">Fine: &#8369;{{ number_format($record->fine_amount, 2) }}</span>
                            @else
                                <span class="text-xs font-semibold text-green-600">No fine</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    const FINE_PER_DAY  = {{ \App\Models\BorrowRecord::FINE_PER_DAY }};
    const checkboxes    = document.querySelectorAll('.return-checkbox');
    const returnBtn     = document.getElementById('returnBtn');
    const selectAll     = document.getElementById('selectAllBtn');
    const summary       = document.getElementById('returnSummary');
    const breakdown     = document.getElementById('fineBreakdown');

    function updateReturn() {
        const selected    = [...checkboxes].filter(c => c.checked);
        const count       = selected.length;
        const totalFine   = selected.reduce((sum, c) => {
            const row  = c.closest('label');
            const days = parseInt(row.dataset.days || 0, 10);
            return sum + days * FINE_PER_DAY;
        }, 0);
        const overdueSelected = selected.filter(c => parseInt(c.closest('label').dataset.days || 0) > 0);

        returnBtn.disabled = count === 0;

        if (count === 0) {
            summary.textContent  = 'Select books above to return them.';
            selectAll.textContent = 'Select all';
            breakdown.classList.add('hidden');
            breakdown.innerHTML = '';
        } else {
            summary.textContent  = `${count} book${count > 1 ? 's' : ''} selected for return.`;
            selectAll.textContent = count === checkboxes.length ? 'Deselect all' : 'Select all';

            if (totalFine > 0) {
                const overdueCount = overdueSelected.length;
                // Show formula: ₱10/day × total_days × books breakdown
                let lines = overdueSelected.map(c => {
                    const row   = c.closest('label');
                    const days  = parseInt(row.dataset.days, 10);
                    const title = row.querySelector('.font-bold.text-stone-800').textContent.trim();
                    return `${title}: ₱${FINE_PER_DAY} × ${days}d = ₱${(days * FINE_PER_DAY).toFixed(2)}`;
                });
                breakdown.innerHTML =
                    `<div class="font-bold mb-1">Fine due on return &mdash; ₱${FINE_PER_DAY}/day &times; overdue days &times; book:</div>`
                    + lines.map(l => `<div>• ${l}</div>`).join('')
                    + `<div class="border-t border-red-200 mt-1.5 pt-1.5 font-bold">Total: ₱${totalFine.toFixed(2)}</div>`;
                breakdown.classList.remove('hidden');
            } else {
                breakdown.classList.add('hidden');
                breakdown.innerHTML = '';
            }
        }
    }

    checkboxes.forEach(c => c.addEventListener('change', updateReturn));

    selectAll.addEventListener('click', function () {
        const allChecked = [...checkboxes].every(c => c.checked);
        checkboxes.forEach(c => { c.checked = !allChecked; });
        updateReturn();
    });

    updateReturn();
</script>
@endpush
