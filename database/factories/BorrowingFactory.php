<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrowing>
 */
class BorrowingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $borrowedAt = Carbon::now()->subDays(fake()->numberBetween(1, 30));
        $dueDate = $borrowedAt->copy()->addDays(14);

        return [
            'member_id' => Member::factory(),
            'book_id' => Book::factory(),
            'borrowed_at' => $borrowedAt,
            'due_date' => $dueDate,
            'returned_at' => null,
            'status' => 'borrowed',
        ];
    }

    /**
     * Indicate that the book has been returned.
     */
    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'returned_at' => Carbon::now(),
            'status' => 'returned',
        ]);
    }

    /**
     * Indicate that the book is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => Carbon::now()->subDays(5),
            'status' => 'overdue',
        ]);
    }
}
