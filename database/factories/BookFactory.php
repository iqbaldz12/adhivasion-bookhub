<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'          => fake()->sentence(3),
            'author'         => fake()->name(),
            'published_year' => fake()->numberBetween(1990, 2025),
            'isbn'           => fake()->unique()->isbn13(),
            'stock'          => fake()->numberBetween(0, 20),
        ];
    }
}
