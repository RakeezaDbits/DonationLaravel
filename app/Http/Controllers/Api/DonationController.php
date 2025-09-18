<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Srmklive\PayPal\Services\PayPal as PayPalClient;


class DonationController extends Controller
{
    // Monthly User Donation APIs
    public function monthlyAmount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Store in session or cache temporarily
        $userId = auth()->id();
        cache()->put("monthly_donation_amount_{$userId}", $request->amount, 3600);

        return response()->json([
            'success' => true,
            'message' => 'Donation amount set successfully',
            'amount' => $request->amount
        ]);
    }

    public function monthlyPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:paypal,bank_transfer',
            'payment_screenshot' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'payment_details' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = auth()->id();
        $amount = cache()->get("monthly_donation_amount_{$userId}");

        if (!$amount) {
            return response()->json(['error' => 'Donation amount not found. Please set amount first.'], 400);
        }

        // Handle file upload
        $screenshotPath = null;
        if ($request->hasFile('payment_screenshot')) {
            $screenshotPath = $request->file('payment_screenshot')->store('donation_screenshots', 'public');
        }

        // Store payment data in cache
        cache()->put("monthly_donation_payment_{$userId}", [
            'payment_method' => $request->payment_method,
            'payment_screenshot' => $screenshotPath,
            'payment_details' => $request->payment_details
        ], 3600);

        return response()->json([
            'success' => true,
            'message' => 'Payment information saved successfully'
        ]);
    }

    public function monthlySubmit(Request $request)
    {
        $userId = auth()->id();
        $user = auth()->user();

        $amount = cache()->get("monthly_donation_amount_{$userId}");
        $paymentData = cache()->get("monthly_donation_payment_{$userId}");

        if (!$amount || !$paymentData) {
            return response()->json(['error' => 'Donation data not found. Please complete the previous steps.'], 400);
        }

        $donation = Donation::create([
            'user_id' => $userId,
            'donor_name' => $user->name,
            'donor_email' => $user->email,
            'donor_phone' => $user->phone,
            'is_anonymous' => $user->is_anonymous,
            'amount' => $amount,
            'payment_method' => $paymentData['payment_method'],
            'payment_screenshot' => $paymentData['payment_screenshot'],
            'payment_details' => $paymentData['payment_details'],
            'donor_type' => 'monthly',
            'status' => 'pending'
        ]);

        // Clear cache
        cache()->forget("monthly_donation_amount_{$userId}");
        cache()->forget("monthly_donation_payment_{$userId}");

        // Create notification for user
        Notification::create([
            'user_id' => $userId,
            'title' => 'Donation Submitted',
            'message' => "Your monthly donation of $" . number_format($amount, 2) . " has been submitted for review.",
            'type' => 'general'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Donation submitted successfully',
            'donation' => $donation
        ], 201);
    }

    // Guest User Donation APIs
    public function guestInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'is_anonymous' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sessionId = $request->header('Session-ID') ?? uniqid();

        cache()->put("guest_donation_info_{$sessionId}", [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_anonymous' => $request->is_anonymous ?? false
        ], 3600);

        return response()->json([
            'success' => true,
            'message' => 'Donor information saved successfully',
            'session_id' => $sessionId
        ]);
    }

    public function guestAmount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'session_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sessionId = $request->session_id;
        cache()->put("guest_donation_amount_{$sessionId}", $request->amount, 3600);

        return response()->json([
            'success' => true,
            'message' => 'Donation amount set successfully',
            'amount' => $request->amount
        ]);
    }

    public function guestPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|string',
            'payment_method' => 'required|in:paypal,bank_transfer',
            'payment_screenshot' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'payment_details' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sessionId = $request->session_id;

        // Handle file upload
        $screenshotPath = null;
        if ($request->hasFile('payment_screenshot')) {
            $screenshotPath = $request->file('payment_screenshot')->store('donation_screenshots', 'public');
        }

        cache()->put("guest_donation_payment_{$sessionId}", [
            'payment_method' => $request->payment_method,
            'payment_screenshot' => $screenshotPath,
            'payment_details' => $request->payment_details
        ], 3600);

        return response()->json([
            'success' => true,
            'message' => 'Payment information saved successfully'
        ]);
    }

    public function guestSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sessionId = $request->session_id;

        $donorInfo = cache()->get("guest_donation_info_{$sessionId}");
        $amount = cache()->get("guest_donation_amount_{$sessionId}");
        $paymentData = cache()->get("guest_donation_payment_{$sessionId}");

        if (!$donorInfo || !$amount || !$paymentData) {
            return response()->json(['error' => 'Donation data not found. Please complete all steps.'], 400);
        }

        $donation = Donation::create([
            'user_id' => null,
            'donor_name' => $donorInfo['name'],
            'donor_email' => $donorInfo['email'],
            'donor_phone' => $donorInfo['phone'],
            'is_anonymous' => $donorInfo['is_anonymous'],
            'amount' => $amount,
            'payment_method' => $paymentData['payment_method'],
            'payment_screenshot' => $paymentData['payment_screenshot'],
            'payment_details' => $paymentData['payment_details'],
            'donor_type' => 'guest',
            'status' => 'pending'
        ]);

        // Clear cache
        cache()->forget("guest_donation_info_{$sessionId}");
        cache()->forget("guest_donation_amount_{$sessionId}");
        cache()->forget("guest_donation_payment_{$sessionId}");

        return response()->json([
            'success' => true,
            'message' => 'Donation submitted successfully',
            'donation' => $donation
        ], 201);
    }

    // Donation History API
    public function history(Request $request)
    {
        $user = auth()->user();

        $query = Donation::where('user_id', $user->id);

        // Apply filters
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        }

        if ($request->has('name')) {
            $query->byDonorName($request->name);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $perPage = $request->get('per_page', 15);
        $donations = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'donations' => $donations
        ]);
    }
    public function paypalCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'currency' => 'nullable|string|size:3'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $order = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => $request->currency ?? "USD",
                        "value" => $request->amount
                    ]
                ]
            ]
        ]);

        foreach ($order['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return response()->json([
                    'success' => true,
                    'approval_url' => $link['href']
                ]);
            }
        }

        return response()->json(['error' => 'Unable to create PayPal order'], 500);
    }

    public function paypalCapture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $result = $provider->capturePaymentOrder($request->token);

        if (isset($result['status']) && $result['status'] === 'COMPLETED') {
            // Yahan sirf PayPal ka confirmation milega
            // User se screenshot baad me liya jayega

            return response()->json([
                'success' => true,
                'message' => 'Payment completed, please upload screenshot.',
                'paypal_data' => $result
            ]);
        }

        return response()->json(['error' => 'Payment not completed', 'details' => $result], 400);
    }


    public function paypalCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'currency' => 'nullable|string|size:3'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $order = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => $request->currency ?? "USD",
                    "value" => $request->amount
                ]
            ]],
            "application_context" => [
                "return_url" => url("/api/donation/paypal/capture"),
                "cancel_url" => url("/api/donation/paypal/cancel")
            ]
        ]);

        foreach ($order['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return response()->json([
                    'success' => true,
                    'approval_url' => $link['href'],
                    'order_id' => $order['id']
                ]);
            }
        }

        return response()->json(['error' => 'Unable to create PayPal order'], 500);
    }
}
