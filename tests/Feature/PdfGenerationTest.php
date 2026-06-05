<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        ]);
    }

    public function test_pdf_endpoint_returns_pdf(): void
    {
        $token = $this->user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/pdf', [
                'title' => 'My Test PDF',
                'body' => "Line one\nLine two\nLine three",
            ]);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('Content-Disposition', 'inline; filename="document.pdf"');

        $this->assertStringStartsWith('%PDF', $response->getContent());
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
}
