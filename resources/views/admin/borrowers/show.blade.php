@extends('layouts.admin')

@section('title', $borrower->name)
@section('heading', $borrower->name)

@section('actions')
    <a href="{{ route('admin.borrowers.index') }}"
        class="inline-flex items-center gap-2 text-sm font-semibold text-stone-600 hover:text-stone-900 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        All Borrowers
    </a>
@endsection

@section('content')
    <div class="grid lg:grid-cols-3 gap-6 items-start">

        {{-- ── Profile card ──────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-8">
            <div class="flex flex-col items-center text-center">
                <div
                    class="w-20 h-20 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white font-extrabold text-3xl mb-4 shadow">
                    {{ strtoupper(substr($borrower->name, 0, 1)) }}
                </div>
                <h2 class="text-xl font-extrabold text-stone-800">{{ $borrower->name }}</h2>
                @if($borrower->email)
                    <p class="text-sm text-stone-400 mt-1">{{ $borrower->email }}</p>
                @endif
                @if($borrower->contact_number)
                    <p class="text-sm text-stone-500 mt-0.5">{{ $borrower->contact_number }}</p>
                @endif
            </div>

            {{-- Stats --}}
            <div class="mt-6 pt-6 border-t border-stone-100 grid grid-cols-2 gap-3">
                <div class="bg-stone-50 rounded-xl p-3 text-center">
                    <p class="text-xl font-extrabold text-stone-800">{{ $borrower->total_borrows }}</p>
                    <p class="text-xs font-semibold text-stone-400 mt-0.5 uppercase tracking-wider">Total</p>
                </div>
                <div class="bg-blue-50 rounded-xl p-3 text-center">
                    <p class="text-xl font-extrabold text-blue-700">{{ $borrower->active_borrows }}</p>
                    <p class="text-xs font-semibold text-blue-400 mt-0.5 uppercase tracking-wider">Active</p>
                </div>
                <div class="{{ $borrower->overdue_borrows > 0 ? 'bg-red-50' : 'bg-stone-50' }} rounded-xl p-3 text-center">
                    <p
                        class="text-xl font-extrabold {{ $borrower->overdue_borrows > 0 ? 'text-red-700' : 'text-stone-400' }}">
                        {{ $borrower->overdue_borrows }}
                    </p>
                    <p
                        class="text-xs font-semibold {{ $borrower->overdue_borrows > 0 ? 'text-red-400' : 'text-stone-400' }} mt-0.5 uppercase tracking-wider">
                        Overdue</p>
                </div>
                <div class="{{ $finesCollected > 0 ? 'bg-orange-50' : 'bg-stone-50' }} rounded-xl p-3 text-center">
                    <p class="text-xl font-extrabold {{ $finesCollected > 0 ? 'text-orange-700' : 'text-stone-400' }}">
                        ₱{{ number_format($finesCollected, 0) }}</p>
                    <p
                        class="text-xs font-semibold {{ $finesCollected > 0 ? 'text-orange-400' : 'text-stone-400' }} mt-0.5 uppercase tracking-wider">
                        Fines Paid</p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-stone-100 space-y-2 text-xs text-stone-400">
                <div class="flex justify-between">
                    <span class="font-semibold">First borrow</span>
                    <span>{{ $borrower->created_at->format('M j, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold">Last seen</span>
                    <span>{{ $borrower->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        {{-- ── Borrow history ─────────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-stone-400 px-1">
                Borrow History ({{ $records->count() }})
            </h3>

            @if($records->isEmpty())
                <div class="bg-white rounded-2xl border border-stone-200 shadow-sm py-20 text-center">
                    <p class="text-stone-400 text-sm">No borrow records.</p>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-stone-50 text-xs uppercase tracking-wider text-stone-400 border-b border-stone-100">
                                <th class="px-5 py-3 text-left">Book</th>
                                <th class="px-5 py-3 text-left hidden sm:table-cell">Borrowed</th>
                                <th class="px-5 py-3 text-left hidden sm:table-cell">Due</th>
                                <th class="px-5 py-3 text-left hidden md:table-cell">Returned</th>
                                <th class="px-5 py-3 text-center">Status</th>
                                <th class="px-5 py-3 text-right">Fine</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-50">
                            @foreach($records as $record)
                                        @php
                                            $isOverdue = $record->isOverdue();
                                            $fine = $record->computeFine();
                                        @endphp
                                        <tr class="hover:bg-stone-50 transition-colors">
                                            <td class="px-5 py-3.5">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-7 h-10 rounded bg-stone-100 overflow-hidden shrink-0">
                                                        @if($record->book?->cover_image)
                                                            <img src="{{ $record->book->cover_image }}" class="w-full h-full object-cover"
                                                                alt="">
                                                        @endif
                                                    </div>
                                                    <p class="font-semibold text-stone-800 leading-snug">{{ $record->book?->title }}</p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-3.5 text-stone-500 hidden sm:table-cell whitespace-nowrap">
                                                {{ $record->borrow_date->format('M j, Y') }}
                                            </td>
                                            <td
                                                class="px-5 py-3.5 hidden sm:table-cell whitespace-nowrap {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-stone-500' }}">
                                                {{ $record->due_date->format('M j, Y') }}
                                            </td>
                                            <td class="px-5 py-3.5 text-stone-500 hidden md:table-cell whitespace-nowrap">
                                                {{ $record->return_date ? $record->return_date->format('M j, Y') : '—' }}
                                            </td>
                                            <td class="px-5 py-3.5 text-center">
                                                <span class="text-xs font-bold px-2.5 py-1 rounded-full
                                                                                {{ $record->status === 'returned'
                                ? 'bg-stone-100 text-stone-500'
                                : ($isOverdue ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                                    {{ $record->status === 'returned' ? 'Returned' : ($isOverdue ? 'Overdue' : 'Active') }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                                @if($fine > 0)
                                                    <span
                                                        class="{{ $record->status === 'returned' ? 'text-stone-500' : 'text-red-600 font-semibold' }}">
                                                        ₱{{ number_format($fine, 2) }}
                                                    </span>
                                                @else
                                                    <span class="text-stone-300">—</span>
                                                @endif
                                            </td>
                                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
@endsection