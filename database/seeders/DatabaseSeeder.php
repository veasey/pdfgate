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
        User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => 'password',
            'is_admin' => true,
            'is_subscribed' => true,
            'pdf_generated_count' => 12,
        ]);

        User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => 'password',
            'is_subscribed' => false,
            'pdf_generated_count' => 3,
        ]);

        // Additional test accounts
        User::updateOrCreate([
            'email' => 'member1@example.com',
        ], [
            'name' => 'Member One',
            'password' => 'password',
            'is_subscribed' => true,
            'pdf_generated_count' => 5,
        ]);

        User::updateOrCreate([
            'email' => 'member2@example.com',
        ], [
            'name' => 'Member Two',
            'password' => 'password',
            'is_subscribed' => false,
            'pdf_generated_count' => 1,
        ]);

        User::updateOrCreate([
            'email' => 'viewer@example.com',
        ], [
            'name' => 'Viewer',
            'password' => 'password',
            'is_subscribed' => false,
            'pdf_generated_count' => 0,
        ]);
    }
}
