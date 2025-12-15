<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class BorrowingTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('auth_token')->plainTextToken;
    }

    /**
     * Test get all borrowings successfully.
     */
    public function test_can_get_all_borrowings(): void
    {
        $member = Member::factory()->create();
        $book = Book::factory()->create();
        Borrowing::factory()->count(2)->create([
            'member_id' => $member->id,
            'book_id' => $book->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/borrowings');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'member_id', 'book_id', 'borrowed_at', 'due_date', 'status']
                ]
            ])
            ->assertJsonCount(2, 'data');
    }

    /**
     * Test create borrowing successfully.
     */
    public function test_can_create_borrowing_successfully(): void
    {
        $member = Member::factory()->create();
        $book = Book::factory()->create(['stock' => 5, 'available_stock' => 5]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/borrowings', [
                'member_id' => $member->id,
                'book_id' => $book->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'member_id', 'book_id', 'status']
            ]);

        $this->assertDatabaseHas('borrowings', [
            'member_id' => $member->id,
            'book_id' => $book->id,
            'status' => 'borrowed',
        ]);

        // Check that available_stock decreased
        $book->refresh();
        $this->assertEquals(4, $book->available_stock);
    }

    /**
     * Test cannot borrow book with zero stock.
     */
    public function test_cannot_borrow_book_with_zero_stock(): void
    {
        $member = Member::factory()->create();
        $book = Book::factory()->create(['stock' => 0, 'available_stock' => 0]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/borrowings', [
                'member_id' => $member->id,
                'book_id' => $book->id,
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Book is not available for borrowing'
            ]);
    }

    /**
     * Test create borrowing with invalid member.
     */
    public function test_cannot_create_borrowing_with_invalid_member(): void
    {
        $book = Book::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/borrowings', [
                'member_id' => 999,
                'book_id' => $book->id,
            ]);

        $response->assertStatus(422);
    }

    /**
     * Test create borrowing with invalid book.
     */
    public function test_cannot_create_borrowing_with_invalid_book(): void
    {
        $member = Member::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/borrowings', [
                'member_id' => $member->id,
                'book_id' => 999,
            ]);

        $response->assertStatus(422);
    }

    /**
     * Test get single borrowing successfully.
     */
    public function test_can_get_single_borrowing(): void
    {
        $member = Member::factory()->create();
        $book = Book::factory()->create();
        $borrowing = Borrowing::factory()->create([
            'member_id' => $member->id,
            'book_id' => $book->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/borrowings/' . $borrowing->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $borrowing->id,
                ]
            ]);
    }

    /**
     * Test get non-existent borrowing.
     */
    public function test_cannot_get_non_existent_borrowing(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/borrowings/999');

        $response->assertStatus(404);
    }

    /**
     * Test return book successfully.
     */
    public function test_can_return_book_successfully(): void
    {
        $member = Member::factory()->create();
        $book = Book::factory()->create(['stock' => 5, 'available_stock' => 4]);
        $borrowing = Borrowing::factory()->create([
            'member_id' => $member->id,
            'book_id' => $book->id,
            'status' => 'borrowed',
            'returned_at' => null,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/borrowings/' . $borrowing->id . '/return');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Book returned successfully',
                'data' => [
                    'status' => 'returned',
                ]
            ]);

        // Check that available_stock increased
        $book->refresh();
        $this->assertEquals(5, $book->available_stock);
    }

    /**
     * Test cannot return already returned book.
     */
    public function test_cannot_return_already_returned_book(): void
    {
        $member = Member::factory()->create();
        $book = Book::factory()->create();
        $borrowing = Borrowing::factory()->create([
            'member_id' => $member->id,
            'book_id' => $book->id,
            'status' => 'returned',
            'returned_at' => Carbon::now(),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/borrowings/' . $borrowing->id . '/return');

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Book has already been returned'
            ]);
    }

    /**
     * Test update borrowing successfully.
     */
    public function test_can_update_borrowing_successfully(): void
    {
        $member = Member::factory()->create();
        $book = Book::factory()->create();
        $borrowing = Borrowing::factory()->create([
            'member_id' => $member->id,
            'book_id' => $book->id,
        ]);

        $newDueDate = Carbon::now()->addDays(7);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/borrowings/' . $borrowing->id, [
                'due_date' => $newDueDate->toDateTimeString(),
            ]);

        $response->assertStatus(200);
    }

    /**
     * Test update borrowing with invalid status.
     */
    public function test_cannot_update_borrowing_with_invalid_status(): void
    {
        $member = Member::factory()->create();
        $book = Book::factory()->create();
        $borrowing = Borrowing::factory()->create([
            'member_id' => $member->id,
            'book_id' => $book->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/borrowings/' . $borrowing->id, [
                'status' => 'invalid_status',
            ]);

        $response->assertStatus(422);
    }

    /**
     * Test delete borrowing successfully.
     */
    public function test_can_delete_borrowing_successfully(): void
    {
        $member = Member::factory()->create();
        $book = Book::factory()->create();
        $borrowing = Borrowing::factory()->create([
            'member_id' => $member->id,
            'book_id' => $book->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/borrowings/' . $borrowing->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Borrowing deleted successfully'
            ]);

        $this->assertDatabaseMissing('borrowings', [
            'id' => $borrowing->id,
        ]);
    }

    /**
     * Test data consistency - multiple borrowings.
     */
    public function test_data_consistency_with_multiple_borrowings(): void
    {
        $member = Member::factory()->create();
        $book = Book::factory()->create(['stock' => 5, 'available_stock' => 5]);

        // Borrow 3 times
        for ($i = 0; $i < 3; $i++) {
            $this->withHeader('Authorization', 'Bearer ' . $this->token)
                ->postJson('/api/borrowings', [
                    'member_id' => $member->id,
                    'book_id' => $book->id,
                ]);
        }

        $book->refresh();
        $this->assertEquals(2, $book->available_stock);
        $this->assertEquals(5, $book->stock);
    }
}
