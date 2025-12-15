<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
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
     * Test get all books successfully.
     */
    public function test_can_get_all_books(): void
    {
        Book::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'title', 'author', 'isbn', 'stock', 'available_stock']
                ]
            ])
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test create book successfully.
     */
    public function test_can_create_book_successfully(): void
    {
        $bookData = [
            'title' => 'Laravel Guide',
            'author' => 'John Doe',
            'isbn' => '978-1234567890',
            'publisher' => 'Tech Publisher',
            'year' => 2024,
            'description' => 'A comprehensive guide to Laravel',
            'stock' => 10,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/books', $bookData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'title', 'author', 'isbn']
            ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Laravel Guide',
            'isbn' => '978-1234567890',
        ]);
    }

    /**
     * Test create book with invalid data.
     */
    public function test_cannot_create_book_with_invalid_data(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/books', [
                'title' => '',
                'author' => '',
                'isbn' => '',
                'year' => 'invalid',
                'stock' => -1,
            ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors'
            ]);
    }

    /**
     * Test create book with duplicate ISBN.
     */
    public function test_cannot_create_book_with_duplicate_isbn(): void
    {
        Book::factory()->create(['isbn' => '978-1234567890']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/books', [
                'title' => 'Another Book',
                'author' => 'Jane Doe',
                'isbn' => '978-1234567890',
                'year' => 2024,
                'stock' => 5,
            ]);

        $response->assertStatus(422);
    }

    /**
     * Test get single book successfully.
     */
    public function test_can_get_single_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/books/' . $book->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'title', 'author', 'isbn']
            ])
            ->assertJson([
                'data' => [
                    'id' => $book->id,
                    'title' => $book->title,
                ]
            ]);
    }

    /**
     * Test get non-existent book.
     */
    public function test_cannot_get_non_existent_book(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/books/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Book not found'
            ]);
    }

    /**
     * Test update book successfully.
     */
    public function test_can_update_book_successfully(): void
    {
        $book = Book::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/books/' . $book->id, [
                'title' => 'Updated Title',
                'author' => 'Updated Author',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'Updated Title',
                    'author' => 'Updated Author',
                ]
            ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Title',
        ]);
    }

    /**
     * Test update non-existent book.
     */
    public function test_cannot_update_non_existent_book(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/books/999', [
                'title' => 'Updated Title',
            ]);

        $response->assertStatus(404);
    }

    /**
     * Test delete book successfully.
     */
    public function test_can_delete_book_successfully(): void
    {
        $book = Book::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/books/' . $book->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Book deleted successfully'
            ]);

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }

    /**
     * Test delete non-existent book.
     */
    public function test_cannot_delete_non_existent_book(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/books/999');

        $response->assertStatus(404);
    }

    /**
     * Test access books without authentication.
     */
    public function test_cannot_access_books_without_auth(): void
    {
        $response = $this->getJson('/api/books');

        $response->assertStatus(401);
    }
}
