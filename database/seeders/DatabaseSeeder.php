<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
            'is_admin' => true,
            'is_subscribed' => true,
            'pdf_generated_count' => 12,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'is_subscribed' => false,
            'pdf_generated_count' => 3,
        ]);

        // Additional test accounts
        User::factory()->create([
            'name' => 'Member One',
            'email' => 'member1@example.com',
            'password' => 'password',
            'is_subscribed' => true,
            'pdf_generated_count' => 5,
        ]);

        User::factory()->create([
            'name' => 'Member Two',
            'email' => 'member2@example.com',
            'password' => 'password',
            'is_subscribed' => false,
            'pdf_generated_count' => 1,
        ]);

        User::factory()->create([
            'name' => 'Viewer',
            'email' => 'viewer@example.com',
            'password' => 'password',
            'is_subscribed' => false,
            'pdf_generated_count' => 0,
        ]);
    }
}
