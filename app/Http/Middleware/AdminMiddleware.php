<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.','redirect' => route('admin.login')], 401);
            }
            return redirect()->guest(route('admin.login'))->with('error', 'Please login to access admin panel.');
        }

        $user = auth()->user();

        // Allowed roles (as stored in DB). Keep original list but we will normalize later.
        $allowedRoles = [
            'donor',
            'admin',
            'super_admin',
            'President',
            'VicePresident',
            'secretary',
            'assistant_secretary',
            'treasurer',
            'assistant_treasurer',
            'committee_member',
        ];

        // 1) Try Spatie first if present
        $userRoles = [];
        if (method_exists($user, 'getRoleNames') && $user->getRoleNames()->isNotEmpty()) {
            $userRoles = $user->getRoleNames()->toArray();
        } else {
            // 2) Fallback to role column (handle many formats)
            $raw = $user->role ?? '';

            // Ensure string
            $raw = is_string($raw) ? $raw : trim((string)$raw);

            // Remove surrounding quotes and non-breaking spaces
            $raw = trim($raw, " \t\n\r\0\x0B\"'");

            // If JSON array/string stored
            if ($raw !== '' && (str_starts_with($raw, '[') || str_starts_with($raw, '{'))) {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    $userRoles = array_values(array_filter(array_map('strval', $decoded)));
                } else {
                    $userRoles = $raw === '' ? [] : [$raw];
                }
            } elseif (preg_match('/[,\|;]/', $raw)) {
                // comma/pipe/semicolon separated
                $userRoles = array_map('trim', preg_split('/[,\|;]+/', $raw));
            } else {
                $userRoles = $raw === '' ? [] : [$raw];
            }
        }

        // Normalizer: trim, convert NBSP to space, strip non-alnum/underscore, lowercase
        $normalize = function ($s) {
            $s = trim((string)$s);
            // replace NBSP and similar
            $s = preg_replace('/\x{00A0}/u', ' ', $s);
            // remove curly quotes and ordinary quotes around value
            $s = trim($s, "\"' ");
            // remove anything except letters, numbers and underscore (so "VicePresident" -> "vicepresident", "vice_president" -> "vice_president")
            $s = preg_replace('/[^a-z0-9_]/i', '', $s);
            return mb_strtolower($s);
        };

        $userRolesNormalized = array_values(array_filter(array_map($normalize, $userRoles)));
        $allowedRolesNormalized = array_values(array_filter(array_map($normalize, $allowedRoles)));

        // intersection
        $intersection = array_intersect($userRolesNormalized, $allowedRolesNormalized);
        $authorized = !empty($intersection);

        // Detailed debug log to catch hidden bytes / length / hex
        Log::info('AdminMiddleware role check detailed', [
            'user_id' => $user->id ?? null,
            'email' => $user->email ?? null,
            'user_role_raw' => $user->role ?? null,
            'user_roles_detected' => $userRoles,
            'user_roles_normalized' => $userRolesNormalized,
            'allowed_roles_normalized' => $allowedRolesNormalized,
            'intersection' => $intersection,
            'request_url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'raw_hex' => is_string($user->role) ? bin2hex($user->role) : null,
            'raw_len' => is_string($user->role) ? mb_strlen($user->role) : null,
        ]);

        if (!$authorized) {
            Log::warning('Unauthorized admin access attempt (detailed)', [
                'user_id' => $user->id ?? null,
                'email' => $user->email ?? null,
                'user_roles' => $userRoles,
                'user_roles_normalized' => $userRolesNormalized,
                'allowed_roles_normalized' => $allowedRolesNormalized,
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access denied.','redirect' => route('admin.login')], 403);
            }

            return redirect()->route('admin.login')->with('error', 'You are not authorized to access the admin panel.');
        }

        return $next($request);
    }
}
