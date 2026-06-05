<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WebUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Login to PDFGate');
    }

    public function test_user_can_login_and_access_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        $dashboard = $this->get('/dashboard');
        $dashboard->assertStatus(200);
        $dashboard->assertSee('PDFs generated');
    }

    public function test_authenticated_user_can_generate_pdf_from_builder(): void
    {
        $user = User::factory()->create([
            'email' => 'user2@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($user);

        $response = $this->post('/pdf-builder', [
            'title' => 'UI PDF Test',
            'body' => 'This is a PDF generated from the UI.',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertSame(1, $user->refresh()->pdf_generated_count);
    }

    public function test_admin_can_view_user_management(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
            'is_admin' => true,
        ]);

        User::factory()->create([ 'email' => 'member@example.com', 'password' => 'password' ]);

        $this->actingAs($admin);

        $response = $this->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('User Management');
        $response->assertSee('member@example.com');
    }

    public function test_non_admin_cannot_view_user_management(): void
    {
        $user = User::factory()->create([
            'email' => 'user3@example.com',
            'password' => 'password',
            'is_admin' => false,
        ]);

        $this->actingAs($user);

        $response = $this->get('/admin/users');

        $response->assertStatus(403);
    }
}
