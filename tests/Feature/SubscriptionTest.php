<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'subscriber@example.com',
            'password' => bcrypt('password'),
            'is_subscribed' => false,
        ]);
    }

    public function test_subscription_status_returns_false_for_new_user(): void
    {
        $token = $this->user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/subscription');

        $response->assertStatus(200)
            ->assertJson([
                'is_subscribed' => false,
            ]);
    }

    public function test_subscribe_endpoint_sets_is_subscribed_true(): void
    {
        $token = $this->user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/subscribe');

        $response->assertStatus(200)
            ->assertJson([
                'is_subscribed' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'is_subscribed' => true,
        ]);
    }

    public function test_subscription_endpoint_uses_authenticated_user(): void
    {
        $token = $this->user->createToken('api-token')->plainTextToken;

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/subscribe')
            ->assertStatus(200);

        $this->assertTrue((bool) $this->user->refresh()->is_subscribed);
    }
}
