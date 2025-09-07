<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@harf.org',
            'phone' => '+1234567890',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'donor_type' => null,
            'is_anonymous' => false,
            'is_active' => true,
            'email_verified_at' => now()
        ]);

        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'moderator@harf.org',
            'phone' => '+1234567891',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'donor_type' => null,
            'is_anonymous' => false,
            'is_active' => true,
            'email_verified_at' => now()
        ]);
    }
}   