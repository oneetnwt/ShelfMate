<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;
use App\Models\Borrower;

class BorrowRecordFactory extends Factory
{
    protected $model = \App\Models\BorrowRecord::class;

    public function definition()
    {
        $borrowDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $dueDate = (clone $borrowDate)->modify('+14 days');

        return [
            'book_id' => Book::factory(),
            'borrower_id' => Borrower::factory(),
            'borrow_date' => $borrowDate,
            'due_date' => $dueDate,
            'return_date' => $this->faker->boolean(70) ? $this->faker->dateTimeBetween($borrowDate, 'now') : null,
        ];
    }
}
