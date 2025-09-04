<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // If request is for admin routes, redirect to admin login
        if ($request->is('admin/*')) {
            return route('admin.login');
        }

        // For API requests, don't redirect
        if ($request->expectsJson()) {
            return null;
        }

        // Default redirect to admin login (since you don't have regular login)
        return route('admin.login');
    }
}