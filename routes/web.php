<?php

use App\Http\Controllers\Admin\AuthorController as AdminAuthorController;
use App\Http\Controllers\Admin\BorrowerController as AdminBorrowerController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Public routes (no auth required) ────────────────────────────────────────

Route::get('/', [BorrowController::class, 'index'])->name('home');

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

Route::get('/borrow', [BorrowController::class, 'create'])->name('borrow.create');
Route::post('/borrow', [BorrowController::class, 'store'])->name('borrow.store');

Route::get('/my-borrows', [BorrowController::class, 'myBorrows'])->name('borrow.my-borrows');
Route::post('/my-borrows/lookup', [BorrowController::class, 'lookup'])->name('borrow.lookup');
Route::post('/my-borrows/return', [BorrowController::class, 'processReturn'])->name('borrow.return');
Route::post('/my-borrows/clear', [BorrowController::class, 'clearSession'])->name('borrow.clear');

// ── Auth traps ───────────────────────────────────────────────────────────────
// Silently redirect anyone probing /login or /register back to home.
// The real admin login lives at /admin/login.

Route::get('/login', fn() => redirect('/'))->middleware('guest');
Route::get('/register', fn() => redirect('/'));

// ── Admin routes (auth required) ─────────────────────────────────────────────

Route::get('/dashboard', function () {
    $stats = [
        'total_books' => \App\Models\Book::count(),
        'total_authors' => \App\Models\Author::count(),
        'total_borrowers' => \App\Models\Borrower::count(),
        'active_borrows' => \App\Models\BorrowRecord::where('status', 'active')->count(),
        'overdue' => \App\Models\BorrowRecord::where('status', 'active')->where('due_date', '<', today())->count(),
        'fines_collected' => \App\Models\BorrowRecord::where('status', 'returned')->where('fine_amount', '>', 0)->sum('fine_amount'),
        'available_books' => \App\Models\Book::where('available_copies', '>', 0)->count(),
    ];
    $recentBorrows = \App\Models\BorrowRecord::with(['book', 'borrower'])
        ->orderByDesc('created_at')->take(8)->get();
    return view('dashboard', compact('stats', 'recentBorrows'));
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('authors', AdminAuthorController::class);
    Route::get('borrowers', [AdminBorrowerController::class, 'index'])->name('borrowers.index');
    Route::get('borrowers/{borrower}', [AdminBorrowerController::class, 'show'])->name('borrowers.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
