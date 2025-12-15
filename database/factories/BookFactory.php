<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'isbn' => fake()->unique()->isbn13(),
            'publisher' => fake()->company(),
            'year' => fake()->numberBetween(2000, 2024),
            'description' => fake()->paragraph(),
            'stock' => fake()->numberBetween(1, 20),
            'available_stock' => function (array $attributes) {
                return $attributes['stock'];
            },
        ];
    }
}
