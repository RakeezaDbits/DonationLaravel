<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Models\Donation;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'donor')->get();

        foreach ($users as $user) {
            // Welcome notification
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Welcome to HARF!',
                'message' => 'Thank you for joining HARF Donation System. Your account has been created successfully.',
                'type' => 'general',
                'is_read' => true,
                'data' => [
                    'account_type' => $user->donor_type,
                    'registration_date' => $user->created_at->format('Y-m-d')
                ],
                'created_at' => $user->created_at->addMinutes(5),
                'updated_at' => $user->created_at->addHours(2)
            ]);

            // Donation approved notifications
            $approvedDonations = Donation::where('user_id', $user->id)
                ->where('status', 'approved')
                ->limit(3)
                ->get();

            foreach ($approvedDonations as $donation) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Donation Approved',
                    'message' => "Your donation of $" . number_format($donation->amount, 2) . " has been approved. Thank you for your generosity!",
                    'type' => 'donation_approved',
                    'is_read' => rand(0, 1),
                    'data' => [
                        'donation_id' => $donation->id,
                        'amount' => $donation->amount,
                        'payment_method' => $donation->payment_method,
                        'approved_date' => $donation->approved_at->format('Y-m-d H:i:s')
                    ],
                    'created_at' => $donation->approved_at->addMinutes(10),
                    'updated_at' => $donation->approved_at->addHours(1)
                ]);
            }

            // Monthly reminder notifications (for monthly donors)
            if ($user->donor_type === 'monthly') {
                for ($i = 1; $i <= 3; $i++) {
                    $reminderDate = Carbon::now()->subMonths($i)->day(1);
                    
                    Notification::create([
                        'user_id' => $user->id,
                        'title' => 'Monthly Donation Reminder',
                        'message' => 'This is a friendly reminder that your monthly donation is due. Thank you for your continued support!',
                        'type' => 'monthly_reminder',
                        'is_read' => $i > 1,
                        'data' => [
                            'reminder_date' => $reminderDate->format('Y-m-d'),
                            'pledge_amount' => rand(25, 200)
                        ],
                        'created_at' => $reminderDate,
                        'updated_at' => $reminderDate->addDays(rand(1, 5))
                    ]);
                }
            }

            // General system notifications
            $generalNotifications = [
                [
                    'title' => 'New Payment Method Available',
                    'message' => 'We have added new payment options to make donations easier for you.',
                    'days_ago' => rand(5, 15)
                ],
                [
                    'title' => 'Impact Report Available',
                    'message' => 'Check out our latest impact report to see how your donations are making a difference.',
                    'days_ago' => rand(20, 30)
                ],
                [
                    'title' => 'System Maintenance Notice',
                    'message' => 'Our system will undergo maintenance on Sunday from 2 AM to 4 AM EST.',
                    'days_ago' => rand(3, 7)
                ]
            ];

            // Add 1-2 general notifications per user randomly
            $selectedNotifications = collect($generalNotifications)->random(rand(1, 2));
            
            foreach ($selectedNotifications as $notification) {
                $createdAt = Carbon::now()->subDays($notification['days_ago']);
                
                Notification::create([
                    'user_id' => $user->id,
                    'title' => $notification['title'],
                    'message' => $notification['message'],
                    'type' => 'general',
                    'is_read' => rand(0, 1),
                    'data' => [
                        'category' => 'system_update',
                        'priority' => 'normal'
                    ],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt->addHours(rand(1, 24))
                ]);
            }
        }

        // Create some admin notifications (these would be for admin users if needed)
        $adminUser = User::where('role', 'super_admin')->first();
        if ($adminUser) {
            Notification::create([
                'user_id' => $adminUser->id,
                'title' => 'New Donations Pending Approval',
                'message' => 'There are ' . Donation::where('status', 'pending')->count() . ' donations waiting for your review.',
                'type' => 'general',
                'is_read' => false,
                'data' => [
                    'pending_count' => Donation::where('status', 'pending')->count(),
                    'priority' => 'high'
                ],
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2)
            ]);
        }
    }
}
