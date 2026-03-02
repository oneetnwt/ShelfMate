<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = \App\Models\Book::class;

    public function definition(): array
    {
        return [
            'title' => rtrim($this->faker->sentence(rand(2, 5)), '.'),
            'description' => $this->faker->paragraph(3),
            'cover_image' => 'https://picsum.photos/seed/' . $this->faker->unique()->numberBetween(1, 1000) . '/200/300',
            'isbn' => $this->faker->unique()->isbn13(),
            'genre' => $this->faker->randomElement(['Fiction', 'Non-fiction', 'Science', 'History', 'Fantasy', 'Biography', 'Mystery', 'Self-help', 'Classic', 'Romance']),
            'published_year' => $this->faker->numberBetween(1950, 2024),
            'total_copies' => 5,
            'available_copies' => 5,
        ];
    }
}