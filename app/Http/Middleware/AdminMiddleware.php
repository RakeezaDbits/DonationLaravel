<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                    'redirect' => route('admin.login')
                ], 401);
            }
            
            return redirect()->guest(route('admin.login'))
                ->with('error', 'Please login to access admin panel.');
        }

        $user = auth()->user();
        
        // Check if user has admin or super_admin role
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            // Log unauthorized access attempt
            \Log::warning('Unauthorized admin access attempt', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);

            auth()->logout();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Access denied.',
                    'redirect' => route('admin.login')
                ], 403);
            }
            
            return redirect()->route('admin.login')
                ->with('error', 'You are not authorized to access the admin panel.');
        }

        return $next($request);
    }
}