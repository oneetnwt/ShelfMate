<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BorrowerFactory extends Factory
{
    protected $model = \App\Models\Borrower::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'contact_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
        ];
    }
}