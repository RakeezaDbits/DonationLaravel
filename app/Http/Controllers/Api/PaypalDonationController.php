<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaypalDonationController extends Controller
{
    private function getAccessToken()
    {
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.client_secret');
        $mode = config('services.paypal.mode', 'sandbox');
        
        $baseUrl = ($mode === 'live') 
            ? 'https://api-m.paypal.com' 
            : 'https://api-m.sandbox.paypal.com';

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Accept-Language' => 'en_US',
        ])->withBasicAuth($clientId, $clientSecret)
          ->asForm()
          ->post($baseUrl . '/v1/oauth2/token', [
            'grant_type' => 'client_credentials'
          ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }
        
        throw new \Exception('Failed to get PayPal access token: ' . $response->body());
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'donor_name' => 'required|string|max:255'
        ]);

        try {
            $accessToken = $this->getAccessToken();
            $mode = config('services.paypal.mode', 'sandbox');
            
            $baseUrl = ($mode === 'live') 
                ? 'https://api-m.paypal.com' 
                : 'https://api-m.sandbox.paypal.com';

            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => config('services.paypal.currency', 'USD'),
                        'value' => number_format($request->amount, 2, '.', ''),
                    ],
                    'description' => "Donation from {$request->donor_name}",
                ]],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'landing_page' => 'NO_PREFERENCE',
                    'user_action' => 'PAY_NOW',
                    'return_url' => url('/api/donation/paypal/success'),
                    'cancel_url' => url('/api/donation/paypal/cancel'),
                ],
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
                'PayPal-Request-Id' => uniqid(),
            ])->post($baseUrl . '/v2/checkout/orders', $orderData);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ], 200);
            }

            return response()->json([
                'error' => 'Failed to create PayPal order',
                'message' => $response->body()
            ], 500);

        } catch (\Exception $e) {
            \Log::error('PayPal Create Order Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'PayPal Integration Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function captureOrder($orderId, Request $request)
    {
        try {
            $accessToken = $this->getAccessToken();
            $mode = config('services.paypal.mode', 'sandbox');
            
            $baseUrl = ($mode === 'live') 
                ? 'https://api-m.paypal.com' 
                : 'https://api-m.sandbox.paypal.com';

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
                'PayPal-Request-Id' => uniqid(),
            ])->post($baseUrl . "/v2/checkout/orders/{$orderId}/capture");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ], 200);
            }

            return response()->json([
                'error' => 'Failed to capture PayPal order',
                'message' => $response->body()
            ], 500);

        } catch (\Exception $e) {
            \Log::error('PayPal Capture Order Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Capture failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}