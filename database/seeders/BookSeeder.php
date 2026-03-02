<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create 10 authors, each with 3 books
        Author::factory(10)->create()->each(function (Author $author) {
            Book::factory(3)->create(['author_id' => $author->id]);
        });
    }
}
