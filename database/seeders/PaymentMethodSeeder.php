<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'PayPal',
                'details' => [
                    'email' => 'donations@harf.org',
                    'instructions' => 'Send payment to our PayPal account and upload screenshot of the transaction.',
                    'currency' => 'USD',
                    'fees' => '2.9% + $0.30 per transaction',
                    'processing_time' => 'Instant'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Bank Transfer',
                'details' => [
                    'bank_name' => 'ABC National Bank',
                    'account_holder' => 'HARF Organization',
                    'account_number' => '1234567890123456',
                    'routing_number' => '123456789',
                    'swift_code' => 'ABCDEFGH',
                    'iban' => 'US64ABCD12345678901234567890',
                    'instructions' => 'Transfer funds to our bank account and upload the receipt or confirmation.',
                    'processing_time' => '1-3 business days'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Stripe',
                'details' => [
                    'public_key' => 'pk_test_example',
                    'webhook_url' => 'https://harf.org/webhooks/stripe',
                    'currency' => 'USD',
                    'instructions' => 'Credit/Debit card payments via Stripe gateway.',
                    'fees' => '2.9% + $0.30 per transaction',
                    'processing_time' => 'Instant'
                ],
                'is_active' => false
            ],
            [
                'name' => 'Cryptocurrency',
                'details' => [
                    'bitcoin_address' => '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2',
                    'ethereum_address' => '0x742d35Cc6574C0532925a3b8D5c5dd659E8B1234',
                    'instructions' => 'Send cryptocurrency to the respective wallet address and upload transaction hash.',
                    'supported_coins' => ['Bitcoin', 'Ethereum', 'Litecoin'],
                    'processing_time' => '10-60 minutes'
                ],
                'is_active' => false
            ]
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
}
