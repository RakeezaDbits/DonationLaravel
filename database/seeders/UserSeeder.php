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

        // Create Monthly Donors
        $monthlyDonors = [
            [
                'name' => 'John Smith',
                'email' => 'john@example.com',
                'phone' => '+1234567892',
                'donor_type' => 'monthly',
                'is_anonymous' => false
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'phone' => '+1234567893',
                'donor_type' => 'monthly',
                'is_anonymous' => false
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael@example.com',
                'phone' => '+1234567894',
                'donor_type' => 'monthly',
                'is_anonymous' => true
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily@example.com',
                'phone' => '+1234567895',
                'donor_type' => 'monthly',
                'is_anonymous' => false
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david@example.com',
                'phone' => '+1234567896',
                'donor_type' => 'monthly',
                'is_anonymous' => false
            ]
        ];

        foreach ($monthlyDonors as $donor) {
            User::create([
                'name' => $donor['name'],
                'email' => $donor['email'],
                'phone' => $donor['phone'],
                'password' => Hash::make('password123'),
                'role' => 'donor',
                'donor_type' => $donor['donor_type'],
                'is_anonymous' => $donor['is_anonymous'],
                'is_active' => true,
                'email_verified_at' => now()
            ]);
        }

        // Create One-time Donors
        $oneTimeDonors = [
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa@example.com',
                'phone' => '+1234567897',
                'is_anonymous' => false
            ],
            [
                'name' => 'Robert Taylor',
                'email' => 'robert@example.com',
                'phone' => '+1234567898',
                'is_anonymous' => true
            ],
            [
                'name' => 'Jennifer Martinez',
                'email' => 'jennifer@example.com',
                'phone' => '+1234567899',
                'is_anonymous' => false
            ]
        ];

        foreach ($oneTimeDonors as $donor) {
            User::create([
                'name' => $donor['name'],
                'email' => $donor['email'],
                'phone' => $donor['phone'],
                'password' => Hash::make('password123'),
                'role' => 'donor',
                'donor_type' => 'one_time',
                'is_anonymous' => $donor['is_anonymous'],
                'is_active' => true,
                'email_verified_at' => now()
            ]);
        }
    }
}   