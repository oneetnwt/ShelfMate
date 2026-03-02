@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    <div class="space-y-6">

        {{-- ── Stat cards ─────────────────────────────────────── --}}
        @php
            $cards = [
                ['label' => 'Total Books', 'value' => number_format($stats["total_books"]), 'color' => 'bg-amber-500', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>'],
                ['label' => 'Authors', 'value' => number_format($stats["total_authors"]), 'color' => 'bg-violet-500', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>'],
                ['label' => 'Borrowers', 'value' => number_format($stats["total_borrowers"]), 'color' => 'bg-sky-500', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>'],
                ['label' => 'Active Borrows', 'value' => number_format($stats["active_borrows"]), 'color' => 'bg-blue-500', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/>'],
                ['label' => 'Overdue', 'value' => number_format($stats["overdue"]), 'color' => 'bg-red-500', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>'],
                ['label' => 'Available', 'value' => number_format($stats["available_books"]), 'color' => 'bg-emerald-500', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>'],
                ['label' => 'Fines Collected', 'value' => '&#8369;' . number_format($stats["fines_collected"], 2), 'color' => 'bg-orange-500', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>'],
            ];
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-4 gap-4">
            @foreach($cards as $card)
                <div class="bg-white rounded-2xl shadow-sm border border-stone-200 p-5">
                    <div class="w-10 h-10 rounded-xl {{ $card['color'] }} flex items-center justify-center mb-3 shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            {!! $card['icon'] !!}
                        </svg>
                    </div>
                    <p class="text-2xl font-extrabold text-stone-800 leading-none">{!! $card['value'] !!}</p>
                    <p class="text-xs font-semibold text-stone-400 mt-1 uppercase tracking-wider">{{ $card['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- ── Quick actions + Recent borrows ─────────────────── --}}
        <div class="grid lg:grid-cols-3 gap-6">

            {{-- Quick actions --}}
            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
                <h2 class="font-bold text-stone-800 mb-4 text-sm uppercase tracking-wider text-stone-400">Quick Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('admin.authors.create') }}"
                        class="flex items-center gap-3 w-full px-4 py-3 text-sm font-semibold text-stone-700 rounded-xl border border-stone-200 hover:border-amber-300 hover:bg-amber-50 hover:text-amber-800 transition-all">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add New Author
                    </a>
                    <a href="{{ route('admin.authors.index') }}"
                        class="flex items-center gap-3 w-full px-4 py-3 text-sm font-semibold text-stone-700 rounded-xl border border-stone-200 hover:border-amber-300 hover:bg-amber-50 hover:text-amber-800 transition-all">
                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        Manage Authors
                    </a>
                    <a href="{{ route('admin.borrowers.index') }}"
                        class="flex items-center gap-3 w-full px-4 py-3 text-sm font-semibold text-stone-700 rounded-xl border border-stone-200 hover:border-amber-300 hover:bg-amber-50 hover:text-amber-800 transition-all">
                        <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        Manage Borrowers
                    </a>
                    <a href="{{ route('books.index') }}" target="_blank"
                        class="flex items-center gap-3 w-full px-4 py-3 text-sm font-semibold text-stone-700 rounded-xl border border-stone-200 hover:border-amber-300 hover:bg-amber-50 hover:text-amber-800 transition-all">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                        View Book Catalog
                    </a>
                    <a href="{{ route('home') }}" target="_blank"
                        class="flex items-center gap-3 w-full px-4 py-3 text-sm font-semibold text-stone-700 rounded-xl border border-stone-200 hover:border-amber-300 hover:bg-amber-50 hover:text-amber-800 transition-all">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Open Public Site
                    </a>
                </div>
            </div>

            {{-- Recent borrows --}}
            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm lg:col-span-2 overflow-hidden">
                <div class="px-6 py-4 border-b border-stone-100 flex items-center justify-between">
                    <h2 class="font-bold text-stone-800">Recent Borrows</h2>
                    @if($stats['overdue'] > 0)
                        <span class="text-xs font-bold bg-red-100 text-red-700 px-3 py-1 rounded-full">
                            {{ $stats['overdue'] }} overdue
                        </span>
                    @endif
                </div>

                @if($recentBorrows->isEmpty())
                    <div class="py-16 text-center text-stone-400 text-sm">No borrow records yet.</div>
                @else
                    <div class="divide-y divide-stone-50">
                        @foreach($recentBorrows as $record)
                                @php $isOverdue = $record->isOverdue(); @endphp
                                <div class="flex items-center gap-3 px-6 py-3">
                                    <div class="w-8 h-10 rounded bg-stone-100 overflow-hidden shrink-0">
                                        @if($record->book?->cover_image)
                                            <img src="{{ $record->book->cover_image }}" class="w-full h-full object-cover" alt="">
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-stone-800 truncate">{{ $record->book?->title }}</p>
                                        <p class="text-xs text-stone-400">{{ $record->borrower?->name }} &bull; due
                                            {{ $record->due_date->format('M j, Y') }}</p>
                                    </div>
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full shrink-0
                                                {{ $record->status === 'returned'
                            ? 'bg-stone-100 text-stone-500'
                            : ($isOverdue ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700') }}">
                                        {{ $record->status === 'returned' ? 'Returned' : ($isOverdue ? 'Overdue' : 'Active') }}
                                    </span>
                                </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection