<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Author;
use App\Models\Book;
use App\Models\Borrower;
use App\Models\BorrowRecord;
use Carbon\Carbon;

class LibrarySeeder extends Seeder
{
    public function run(): void
    {
        // 15 authors
        $authors = Author::factory(15)->create();

        // 50 books — each gets 1 to 3 random authors attached via pivot
        $books = Book::factory(50)->create();
        foreach ($books as $book) {
            $count = rand(1, 3);
            $book->authors()->attach(
                $authors->random($count)->pluck('id')->toArray()
            );
        }

        // 40 borrowers
        $borrowers = Borrower::factory(40)->create();

        // 120 borrow records spread across statuses
        foreach (range(1, 120) as $i) {
            $book = $books->random();
            $borrower = $borrowers->random();
            $rand = rand(1, 100);

            if ($rand <= 60) {
                // Returned
                $borrowDate = Carbon::now()->subDays(rand(15, 60));
                $dueDate = $borrowDate->copy()->addDays(14);
                $returnDate = $borrowDate->copy()->addDays(rand(1, 20));
                $overdueDays = max(0, (int) $returnDate->diffInDays($dueDate, false) * -1);
                $fine = $overdueDays * BorrowRecord::FINE_PER_DAY;

                BorrowRecord::create([
                    'book_id' => $book->id,
                    'borrower_id' => $borrower->id,
                    'borrow_date' => $borrowDate,
                    'due_date' => $dueDate,
                    'return_date' => $returnDate,
                    'status' => 'returned',
                    'fine_amount' => $fine,
                ]);
            } elseif ($rand <= 90) {
                // Active, not overdue
                $borrowDate = Carbon::now()->subDays(rand(1, 10));
                $dueDate = $borrowDate->copy()->addDays(14);

                BorrowRecord::create([
                    'book_id' => $book->id,
                    'borrower_id' => $borrower->id,
                    'borrow_date' => $borrowDate,
                    'due_date' => $dueDate,
                    'return_date' => null,
                    'status' => 'active',
                    'fine_amount' => 0,
                ]);
            } else {
                // Active, overdue
                $borrowDate = Carbon::now()->subDays(rand(20, 45));
                $dueDate = $borrowDate->copy()->addDays(14);

                BorrowRecord::create([
                    'book_id' => $book->id,
                    'borrower_id' => $borrower->id,
                    'borrow_date' => $borrowDate,
                    'due_date' => $dueDate,
                    'return_date' => null,
                    'status' => 'active',
                    'fine_amount' => 0,
                ]);
            }
        }

        // Recompute available_copies for every book
        foreach ($books as $book) {
            $activeCount = BorrowRecord::where('book_id', $book->id)
                ->where('status', 'active')
                ->count();
            $book->update(['available_copies' => max(0, $book->total_copies - $activeCount)]);
        }
    }
}