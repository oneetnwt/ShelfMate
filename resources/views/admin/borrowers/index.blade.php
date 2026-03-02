@extends('layouts.admin')

@section('title', 'Borrowers')
@section('heading', 'Borrowers')

@section('content')
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">

        {{-- Search bar --}}
        <div class="px-6 py-4 border-b border-stone-100">
            <form method="GET" action="{{ route('admin.borrowers.index') }}" class="flex gap-3">
                <input type="text" name="q" value="{{ $q }}" placeholder="Search by name, email, or contact number..."
                    class="flex-1 text-sm border border-stone-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                <button type="submit"
                    class="text-sm font-semibold bg-stone-100 hover:bg-stone-200 text-stone-700 px-4 py-2 rounded-lg transition-colors">
                    Search
                </button>
                @if($q)
                    <a href="{{ route('admin.borrowers.index') }}"
                        class="text-sm font-semibold text-stone-500 hover:text-stone-700 px-3 py-2 rounded-lg transition-colors">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        @if($borrowers->isEmpty())
            <div class="py-24 text-center">
                <svg class="mx-auto w-10 h-10 text-stone-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                <p class="text-stone-400 text-sm font-medium">No borrowers found.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-stone-50 text-xs uppercase tracking-wider text-stone-400 border-b border-stone-100">
                        <th class="px-6 py-3 text-left">Borrower</th>
                        <th class="px-6 py-3 text-left hidden md:table-cell">Contact</th>
                        <th class="px-6 py-3 text-center">Total</th>
                        <th class="px-6 py-3 text-center">Active</th>
                        <th class="px-6 py-3 text-center">Overdue</th>
                        <th class="px-6 py-3 text-right hidden sm:table-cell">Fines Paid</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @foreach($borrowers as $borrower)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($borrower->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.borrowers.show', $borrower) }}"
                                            class="font-semibold text-stone-800 hover:text-amber-600 transition-colors">
                                            {{ $borrower->name }}
                                        </a>
                                        @if($borrower->email)
                                            <p class="text-xs text-stone-400">{{ $borrower->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-stone-500 hidden md:table-cell">
                                {{ $borrower->contact_number ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-semibold text-stone-700">{{ $borrower->total_borrows }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($borrower->active_borrows > 0)
                                    <span class="inline-block bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                        {{ $borrower->active_borrows }}
                                    </span>
                                @else
                                    <span class="text-stone-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($borrower->overdue_borrows > 0)
                                    <span class="inline-block bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                        {{ $borrower->overdue_borrows }}
                                    </span>
                                @else
                                    <span class="text-stone-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right hidden sm:table-cell">
                                @if($borrower->fines_collected > 0)
                                    <span class="text-orange-600 font-semibold">
                                        ₱{{ number_format($borrower->fines_collected, 2) }}
                                    </span>
                                @else
                                    <span class="text-stone-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.borrowers.show', $borrower) }}"
                                    class="text-stone-400 hover:text-amber-600 transition-colors p-1 rounded inline-block"
                                    title="View history">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($borrowers->hasPages())
                <div class="px-6 py-4 border-t border-stone-100">
                    {{ $borrowers->links() }}
                </div>
            @endif
        @endif

    </div>
@endsection