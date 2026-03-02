<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBorrowRequest;
use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\Borrower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowController extends Controller
{
    // ── Public book catalogue ────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Book::with('authors');

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('genre', 'like', "%{$search}%")
                    ->orWhereHas('authors', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        if ($genre = $request->query('genre')) {
            $query->where('genre', $genre);
        }

        if ($request->query('status') === 'available') {
            $query->where('available_copies', '>', 0);
        } elseif ($request->query('status') === 'borrowed') {
            $query->where('available_copies', 0);
        }

        $books = $query->orderBy('title')->paginate(12)->withQueryString();
        $totalBooks = Book::count();
        $available = Book::where('available_copies', '>', 0)->count();
        $borrowed = $totalBooks - $available;
        $genres = Book::whereNotNull('genre')->distinct()->orderBy('genre')->pluck('genre');

        return view('welcome', compact('books', 'totalBooks', 'available', 'borrowed', 'genres'));
    }

    // ── Borrow form ──────────────────────────────────────────────────────────

    public function create(Request $request)
    {
        $preselected = array_filter((array) $request->query('books', []));
        $availableBooks = Book::with('authors')
            ->available()
            ->orderBy('title')
            ->get();

        return view('borrow.create', compact('availableBooks', 'preselected'));
    }

    // ── Store borrow ─────────────────────────────────────────────────────────

    public function store(StoreBorrowRequest $request)
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated, $request) {
            // Find or create borrower by name + contact_number
            $borrower = Borrower::firstOrCreate(
                [
                    'name' => $validated['name'],
                    'contact_number' => $validated['contact_number'] ?? null,
                ],
                ['email' => $validated['email'] ?? null]
            );

            $borrowDate = today();
            $dueDate = today()->addDays(14);

            // Lock rows to prevent race conditions on available_copies
            $books = Book::whereIn('id', $validated['book_ids'])
                ->where('available_copies', '>', 0)
                ->lockForUpdate()
                ->get();

            if ($books->isEmpty()) {
                return back()
                    ->withErrors(['book_ids' => 'None of the selected books are currently available.'])
                    ->withInput();
            }

            foreach ($books as $book) {
                BorrowRecord::create([
                    'book_id' => $book->id,
                    'borrower_id' => $borrower->id,
                    'borrow_date' => $borrowDate,
                    'due_date' => $dueDate,
                    'status' => 'active',
                    'fine_amount' => 0,
                ]);
                $book->decrement('available_copies');
            }

            // Persist borrower identity in session
            $request->session()->put('borrower_id', $borrower->id);

            $unavailable = count($validated['book_ids']) - $books->count();
            $msg = $books->count() . ' book(s) borrowed! Due by ' . $dueDate->format('F j, Y') . '.';
            if ($unavailable > 0) {
                $msg .= " ({$unavailable} book(s) were no longer available and were skipped.)";
            }

            return redirect()->route('borrow.my-borrows')->with('success', $msg);
        });
    }

    // ── My Borrows ───────────────────────────────────────────────────────────

    public function myBorrows(Request $request)
    {
        $borrowerId = $request->session()->get('borrower_id');

        if (!$borrowerId || !($borrower = Borrower::find($borrowerId))) {
            $request->session()->forget('borrower_id');
            return view('borrow.lookup');
        }

        $activeRecords = BorrowRecord::with('book.authors')
            ->where('borrower_id', $borrower->id)
            ->active()
            ->orderBy('due_date')
            ->get()
            ->each(function ($record) {
                $record->days_overdue = $record->daysOverdue();
                $record->fine_preview = $record->computeFine();
            });

        $returnedRecords = BorrowRecord::with('book.authors')
            ->where('borrower_id', $borrower->id)
            ->returned()
            ->orderByDesc('return_date')
            ->take(20)
            ->get();

        $totalFinePreview = $activeRecords->sum('fine_preview');

        return view('borrow.my-borrows', compact(
            'borrower',
            'activeRecords',
            'returnedRecords',
            'totalFinePreview'
        ));
    }

    // ── Borrow lookup (find by name + contact) ───────────────────────────────

    public function lookup(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:30'],
        ]);

        $borrower = Borrower::where('name', $request->name)
            ->where('contact_number', $request->contact_number ?: null)
            ->first();

        if (!$borrower) {
            return back()
                ->withErrors(['name' => 'No borrower found with that name and contact number.'])
                ->withInput();
        }

        $request->session()->put('borrower_id', $borrower->id);

        return redirect()->route('borrow.my-borrows');
    }

    // ── Process return ───────────────────────────────────────────────────────

    public function processReturn(Request $request)
    {
        $request->validate([
            'record_ids' => ['required', 'array', 'min:1'],
            'record_ids.*' => ['required', 'integer', 'exists:borrow_records,id'],
        ]);

        $borrowerId = $request->session()->get('borrower_id');
        if (!$borrowerId) {
            return redirect()->route('borrow.my-borrows');
        }

        return DB::transaction(function () use ($request, $borrowerId) {
            $records = BorrowRecord::with('book')
                ->where('borrower_id', $borrowerId)
                ->active()
                ->whereIn('id', $request->record_ids)
                ->lockForUpdate()
                ->get();

            if ($records->isEmpty()) {
                return redirect()->route('borrow.my-borrows')
                    ->withErrors(['record_ids' => 'No valid active records found to return.']);
            }

            $totalFine = 0;
            $returnDate = today();
            $overdueLines = [];

            foreach ($records as $record) {
                // Fine = ₱10 × days_overdue × 1 book (per record)
                $days = $record->daysOverdue();
                $fine = $days * BorrowRecord::FINE_PER_DAY;
                $totalFine += $fine;

                if ($days > 0) {
                    $overdueLines[] = "'{$record->book->title}' — ₱10 × {$days}d = ₱" . number_format($fine, 2);
                }

                $record->update([
                    'status' => 'returned',
                    'return_date' => $returnDate,
                    'fine_amount' => $fine,
                ]);

                // Restore copy — cap at total_copies to prevent over-increment
                $book = $record->book;
                $book->available_copies = min(
                    $book->total_copies,
                    $book->available_copies + 1
                );
                $book->save();
            }

            $bookCount = $records->count();
            $msg = $bookCount . ' book' . ($bookCount > 1 ? 's' : '') . ' returned successfully.';

            if ($totalFine > 0) {
                $msg .= ' Fine due: ₱' . number_format($totalFine, 2)
                    . ' (₱' . BorrowRecord::FINE_PER_DAY . '/day × '
                    . implode(', ', array_map(fn($l) => $l, $overdueLines)) . ').';
            }

            return redirect()->route('borrow.my-borrows')
                ->with('success', $msg)
                ->with('total_fine', $totalFine);
        });
    }

    // ── Clear session (switch borrower) ─────────────────────────────────────

    public function clearSession(Request $request)
    {
        $request->session()->forget('borrower_id');
        return redirect()->route('home');
    }
}
