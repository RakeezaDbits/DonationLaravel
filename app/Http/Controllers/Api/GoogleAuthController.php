<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Google_Client;

class GoogleAuthController extends Controller
{
    public function loginWithGoogle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Verify token with Google
            $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
            $payload = $client->verifyIdToken($request->id_token);

            if (!$payload) {
                return response()->json(['error' => 'Invalid Google token'], 401);
            }

            $email = $payload['email'];
            $name = $payload['name'] ?? 'Google User';

            // Find or create user
            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'role' => 'donor',
                    'donor_type' => 'monthly', // ✅ always monthly
                    'is_anonymous' => false,
                    'password' => bcrypt(str()->random(16)),
                ]);
            }

            // Generate JWT token
            $token = Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Login successful with Google',
                'user' => $user,
                'token' => $token
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Google authentication failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
