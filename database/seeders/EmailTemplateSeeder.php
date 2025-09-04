<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Welcome Email',
                'subject' => 'Welcome to HARF - Thank you for joining us!',
                'body' => 'Dear {name},

Welcome to HARF Donation System! Thank you for creating an account and joining our mission to make a positive difference in the world.

Your account has been successfully created with the following details:
- Email: {email}
- Account Type: {donor_type}
- Registration Date: {date}

You can now:
✓ Make monthly or one-time donations
✓ Track your donation history
✓ Receive updates about our activities and impact
✓ Manage your account preferences

If you have any questions or need assistance, please don\'t hesitate to contact our support team at support@harf.org.

Thank you for your trust and support!

Best regards,
The HARF Team

---
HARF Organization
Email: info@harf.org
Phone: +1 (555) 123-4567
Website: https://harf.org',
                'type' => 'welcome',
                'is_active' => true
            ],
            [
                'name' => 'Donation Approved',
                'subject' => 'Your donation has been approved - Thank you!',
                'body' => 'Dear {name},

Great news! Your generous donation has been approved and processed successfully.

Donation Details:
- Amount: ${amount}
- Payment Method: {payment_method}
- Date Submitted: {submitted_date}
- Date Approved: {approved_date}
- Reference Number: {reference}

Your contribution makes a real difference in the lives of those we serve. Thanks to donors like you, we can continue our mission to provide help and support to those in need.

Impact of Your Donation:
- Your ${amount} donation can provide [specific impact based on amount]
- Total donations this month: ${monthly_total}
- Number of people helped: {people_helped}

You can view your complete donation history by logging into your account at any time.

Once again, thank you for your kindness and generosity!

Best regards,
The HARF Team

---
HARF Organization
Email: info@harf.org
Phone: +1 (555) 123-4567
Website: https://harf.org',
                'type' => 'donation_approved',
                'is_active' => true
            ],
            [
                'name' => 'Donation Rejected',
                'subject' => 'Update on your donation submission',
                'body' => 'Dear {name},

Thank you for your donation submission. Unfortunately, we were unable to process your donation at this time.

Submission Details:
- Amount: ${amount}
- Payment Method: {payment_method}
- Date Submitted: {submitted_date}
- Reason: {rejection_reason}

What you can do next:
1. Review the payment screenshot/details you submitted
2. Ensure the payment information is clear and complete
3. Re-submit your donation with corrected information
4. Contact our support team if you need assistance

If you believe this was an error or if you have questions about the rejection, please don\'t hesitate to contact our support team at support@harf.org with your reference number: {reference}

We appreciate your desire to support our cause and apologize for any inconvenience.

Best regards,
The HARF Team

---
HARF Organization
Email: info@harf.org
Phone: +1 (555) 123-4567
Website: https://harf.org',
                'type' => 'donation_rejected',
                'is_active' => true
            ],
            [
                'name' => 'Monthly Reminder',
                'subject' => 'Monthly Donation Reminder - HARF',
                'body' => 'Dear {name},

This is a friendly reminder that your monthly donation is due.

Your Monthly Pledge:
- Pledged Amount: ${monthly_amount}
- Next Due Date: {due_date}
- Last Donation: {last_donation_date}

To make your monthly donation:
1. Log in to your account at https://harf.org/login
2. Navigate to the donation section
3. Complete your monthly contribution

Your Impact So Far:
- Total donations to date: ${total_donated}
- Number of donations made: {donation_count}
- Lives impacted: {impact_stats}

Your consistent support allows us to plan ahead and make a sustained impact in our community. Every monthly contribution, no matter the size, makes a meaningful difference.

If you need to update your pledge amount or have any questions, please contact us at support@harf.org.

Thank you for your continued generosity and commitment!

Best regards,
The HARF Team

---
HARF Organization
Email: info@harf.org
Phone: +1 (555) 123-4567
Website: https://harf.org

P.S. You can manage your monthly reminders and preferences in your account settings.',
                'type' => 'monthly_reminder',
                'is_active' => true
            ]
        ];

        foreach ($templates as $template) {
            EmailTemplate::create($template);
        }
    }
}