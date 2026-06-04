<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup for tests
     */
    public function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    /**
     * Test login with valid credentials
     */
    public function test_login_with_valid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);

        $this->assertNotNull($response->json('token'));
    }

    /**
     * Test login with invalid email
     */
    public function test_login_with_invalid_email(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials',
            ]);
    }

    /**
     * Test login with invalid password
     */
    public function test_login_with_invalid_password(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials',
            ]);
    }

    /**
     * Test login with missing email
     */
    public function test_login_with_missing_email(): void
    {
        $response = $this->postJson('/api/login', [
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test login with missing password
     */
    public function test_login_with_missing_password(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test get user info with valid token
     */
    public function test_get_user_with_valid_token(): void
    {
        $token = $this->user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJson([
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]);
    }

    /**
     * Test get user without token
     */
    public function test_get_user_without_token(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    /**
     * Test get user with invalid token
     */
    public function test_get_user_with_invalid_token(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer invalid-token')
            ->getJson('/api/user');

        $response->assertStatus(401);
    }

    /**
     * Test logout revokes current token
     */
    public function test_logout_revokes_current_token(): void
    {
        $token = $this->user->createToken('api-token')->plainTextToken;

        // Verify token works before logout
        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/user');
        $this->assertTrue($response->status() === 200);

        // Logout
        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logged out successfully',
            ]);

        // Create a fresh client to ensure no cached auth state
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $this->user->id,
            'name' => 'api-token',
        ]);
    }

    /**
     * Test create token
     */
    public function test_create_token(): void
    {
        $token = $this->user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/tokens', [
                'name' => 'mobile-app',
                'abilities' => ['*'],
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
            ]);

        $this->assertNotNull($response->json('token'));

        // New token should work
        $newToken = $response->json('token');
        $userResponse = $this->withHeader('Authorization', "Bearer $newToken")
            ->getJson('/api/user');

        $userResponse->assertStatus(200);
    }

    /**
     * Test create token with default name
     */
    public function test_create_token_with_default_name(): void
    {
        $token = $this->user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/tokens');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
            ]);

        // Verify token works
        $newToken = $response->json('token');
        $userResponse = $this->withHeader('Authorization', "Bearer $newToken")
            ->getJson('/api/user');

        $userResponse->assertStatus(200);
    }

    /**
     * Test revoke token
     */
    public function test_revoke_token(): void
    {
        $token1 = $this->user->createToken('token1')->plainTextToken;
        $token2 = $this->user->createToken('token2')->plainTextToken;

        // Get token ID from database
        $tokenId = $this->user->tokens()->first()->id;

        // Verify both tokens work
        $response1 = $this->withHeader('Authorization', "Bearer $token1")
            ->getJson('/api/user');
        $this->assertTrue($response1->status() === 200);

        $response2 = $this->withHeader('Authorization', "Bearer $token2")
            ->getJson('/api/user');
        $this->assertTrue($response2->status() === 200);

        // Revoke first token
        $response = $this->withHeader('Authorization', "Bearer $token1")
            ->deleteJson("/api/tokens/$tokenId");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Token revoked successfully',
            ]);

        // Verify token was deleted from database
        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $tokenId,
        ]);

        // Second token should still work
        $response = $this->withHeader('Authorization', "Bearer $token2")
            ->getJson('/api/user');

        $response->assertStatus(200);
    }

    /**
     * Test multiple tokens for same user
     */
    public function test_multiple_tokens_for_same_user(): void
    {
        $token1 = $this->user->createToken('mobile')->plainTextToken;
        $token2 = $this->user->createToken('web')->plainTextToken;

        // Both tokens should work
        $response1 = $this->withHeader('Authorization', "Bearer $token1")
            ->getJson('/api/user');
        $response1->assertStatus(200);

        $response2 = $this->withHeader('Authorization', "Bearer $token2")
            ->getJson('/api/user');
        $response2->assertStatus(200);

        // Verify they're different tokens
        $this->assertNotEquals($token1, $token2);
    }

    /**
     * Test token with custom abilities
     */
    public function test_create_token_with_custom_abilities(): void
    {
        $token = $this->user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/tokens', [
                'name' => 'limited-token',
                'abilities' => ['read', 'write'],
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
            ]);

        // Token should still be able to access endpoints
        $newToken = $response->json('token');
        $userResponse = $this->withHeader('Authorization', "Bearer $newToken")
            ->getJson('/api/user');

        $userResponse->assertStatus(200);
    }
}
