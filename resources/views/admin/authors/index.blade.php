@extends('layouts.admin')

@section('title', 'Authors')
@section('heading', 'Authors')

@section('actions')
    <a href="{{ route('admin.authors.create') }}"
       class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-white text-sm font-bold px-4 py-2 rounded-xl transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        New Author
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">

    {{-- Search bar --}}
    <div class="px-6 py-4 border-b border-stone-100">
        <form method="GET" action="{{ route('admin.authors.index') }}" class="flex gap-3">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Search authors by name..."
                   class="flex-1 text-sm border border-stone-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
            <button type="submit"
                    class="text-sm font-semibold bg-stone-100 hover:bg-stone-200 text-stone-700 px-4 py-2 rounded-lg transition-colors">
                Search
            </button>
            @if(request('q'))
                <a href="{{ route('admin.authors.index') }}"
                   class="text-sm font-semibold text-stone-500 hover:text-stone-700 px-3 py-2 rounded-lg transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    @if($authors->isEmpty())
        <div class="py-24 text-center">
            <svg class="mx-auto w-10 h-10 text-stone-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
            </svg>
            <p class="text-stone-400 text-sm font-medium">No authors found.</p>
        </div>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-stone-50 text-xs uppercase tracking-wider text-stone-400 border-b border-stone-100">
                    <th class="px-6 py-3 text-left">Author</th>
                    <th class="px-6 py-3 text-left">Bio</th>
                    <th class="px-6 py-3 text-center">Books</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-50">
                @foreach($authors as $author)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                    {{ strtoupper(substr($author->name, 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.authors.show', $author) }}"
                                       class="font-semibold text-stone-800 hover:text-amber-600 transition-colors">
                                        {{ $author->name }}
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-stone-500 max-w-xs">
                            @if($author->bio)
                                {{ Str::limit($author->bio, 80) }}
                            @else
                                <span class="text-stone-300 italic">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                {{ $author->books_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.authors.show', $author) }}"
                                   class="text-stone-400 hover:text-amber-600 transition-colors p-1 rounded" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.authors.edit', $author) }}"
                                   class="text-stone-400 hover:text-blue-600 transition-colors p-1 rounded" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.authors.destroy', $author) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($author->name) }}? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-stone-400 hover:text-red-600 transition-colors p-1 rounded" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($authors->hasPages())
            <div class="px-6 py-4 border-t border-stone-100">
                {{ $authors->links() }}
            </div>
        @endif
    @endif

</div>
@endsection