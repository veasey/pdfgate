<?php

namespace Tests\Feature;

use App\Jobs\GeneratePdfJob;
use App\Models\PdfJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PdfGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'pdf@example.com',
            'password' => bcrypt('password'),
            'is_subscribed' => true,
            'subscribed_until' => now()->addMonth(),
        ]);
    }

    public function test_pdf_endpoint_queues_pdf_job(): void
    {
        Queue::fake();

        $token = $this->user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/pdf', [
                'title' => 'My Test PDF',
                'body' => "Line one\nLine two\nLine three",
            ]);

        $response->assertStatus(202)
            ->assertJsonStructure([
                'id',
                'status',
                'payload',
                'download_url',
                'created_at',
                'updated_at',
            ])
            ->assertJson(['status' => PdfJob::STATUS_PENDING]);

        Queue::assertPushed(GeneratePdfJob::class, function ($job) {
            return $job->pdfJob->payload['title'] === 'My Test PDF';
        });
    }

    public function test_pdf_endpoint_requires_authentication(): void
    {
        $response = $this->postJson('/api/pdf', [
            'title' => 'My Test PDF',
            'body' => 'Test body',
        ]);

        $response->assertStatus(401);
    }

    public function test_pdf_endpoint_validates_input(): void
    {
        $token = $this->user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/pdf', [
                'body' => 'Test body without title',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_pdf_endpoint_requires_active_subscription(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => bcrypt('password'),
            'is_subscribed' => false,
            'subscribed_until' => null,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/pdf', [
                'title' => 'My Test PDF',
                'body' => 'Test body',
            ]);

        $response->assertStatus(402)
            ->assertJson(["message" => 'Subscription required.']);
    }
}
