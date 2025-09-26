<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

class PayPalClient
{
    public static function client()
    {
        $clientId     = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.client_secret');
        $mode         = config('services.paypal.mode', 'sandbox');

        // Validate credentials
        if (empty($clientId) || empty($clientSecret)) {
            throw new \Exception('PayPal credentials are not properly configured');
        }

        if ($mode === 'live' || $mode === 'production') {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        } else {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        }

        $client = new PayPalHttpClient($environment);
        
        // Set default headers for all requests
        $client->addInjector(function ($request) {
            $request->headers["Content-Type"] = "application/json";
            $request->headers["Accept"] = "application/json";
        });

        return $client;
    }
}