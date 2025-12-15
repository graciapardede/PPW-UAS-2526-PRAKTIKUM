<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberTest extends TestCase
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
     * Test get all members successfully.
     */
    public function test_can_get_all_members(): void
    {
        Member::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/members');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'email', 'phone', 'member_number']
                ]
            ])
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test create member successfully.
     */
    public function test_can_create_member_successfully(): void
    {
        $memberData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 123',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/members', $memberData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'name', 'email', 'member_number']
            ]);

        $this->assertDatabaseHas('members', [
            'email' => 'john@example.com',
        ]);
    }

    /**
     * Test create member with invalid email.
     */
    public function test_cannot_create_member_with_invalid_email(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/members', [
                'name' => 'John Doe',
                'email' => 'invalid-email',
                'phone' => '081234567890',
            ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors'
            ]);
    }

    /**
     * Test create member with duplicate email.
     */
    public function test_cannot_create_member_with_duplicate_email(): void
    {
        Member::factory()->create(['email' => 'john@example.com']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/members', [
                'name' => 'Jane Doe',
                'email' => 'john@example.com',
                'phone' => '081234567890',
            ]);

        $response->assertStatus(422);
    }

    /**
     * Test create member with missing required fields.
     */
    public function test_cannot_create_member_with_missing_fields(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/members', [
                'name' => 'John Doe',
            ]);

        $response->assertStatus(422);
    }

    /**
     * Test get single member successfully.
     */
    public function test_can_get_single_member(): void
    {
        $member = Member::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/members/' . $member->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $member->id,
                    'email' => $member->email,
                ]
            ]);
    }

    /**
     * Test get non-existent member.
     */
    public function test_cannot_get_non_existent_member(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/members/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Member not found'
            ]);
    }

    /**
     * Test update member successfully.
     */
    public function test_can_update_member_successfully(): void
    {
        $member = Member::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/members/' . $member->id, [
                'name' => 'Updated Name',
                'phone' => '089999999999',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Updated Name',
                    'phone' => '089999999999',
                ]
            ]);
    }

    /**
     * Test update member with invalid email.
     */
    public function test_cannot_update_member_with_invalid_email(): void
    {
        $member = Member::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/members/' . $member->id, [
                'email' => 'invalid-email',
            ]);

        $response->assertStatus(422);
    }

    /**
     * Test delete member successfully.
     */
    public function test_can_delete_member_successfully(): void
    {
        $member = Member::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/members/' . $member->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Member deleted successfully'
            ]);

        $this->assertDatabaseMissing('members', [
            'id' => $member->id,
        ]);
    }

    /**
     * Test delete non-existent member.
     */
    public function test_cannot_delete_non_existent_member(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/members/999');

        $response->assertStatus(404);
    }
}
