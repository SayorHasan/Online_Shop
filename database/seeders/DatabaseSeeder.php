<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create regular user account
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'mobile' => '1234567890',
            'utype' => 'USR',
            'password' => Hash::make('password'), // Explicitly set password
        ]);
        
        // Create admin user account
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'mobile' => '0987654321',
            'utype' => 'ADM',
            'password' => Hash::make('password'), // Explicitly set password
        ]);
    }
}
