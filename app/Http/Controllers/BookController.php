<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('authors');

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('genre', 'like', "%{$search}%")
                    ->orWhereHas('authors', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        if ($genre = $request->query('genre')) {
            $query->where('genre', $genre);
        }

        if ($request->query('status') === 'available') {
            $query->where('available_copies', '>', 0);
        } elseif ($request->query('status') === 'unavailable') {
            $query->where('available_copies', 0);
        }

        $books = $query->orderBy('title')->paginate(15)->withQueryString();
        $genres = Book::whereNotNull('genre')->distinct()->orderBy('genre')->pluck('genre');
        $total = Book::count();
        $avail = Book::where('available_copies', '>', 0)->count();

        return view('books.index', compact('books', 'genres', 'total', 'avail'));
    }

    public function show(Book $book)
    {
        $book->load(['authors']);

        $activeBorrows = $book->borrowRecords()
            ->with('borrower')
            ->where('status', 'active')
            ->orderByDesc('borrow_date')
            ->get();

        $recentReturns = $book->borrowRecords()
            ->with('borrower')
            ->where('status', 'returned')
            ->orderByDesc('return_date')
            ->take(5)
            ->get();

        return view('books.show', compact('book', 'activeBorrows', 'recentReturns'));
    }
}
