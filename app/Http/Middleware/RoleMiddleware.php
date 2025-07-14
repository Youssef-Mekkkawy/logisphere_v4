<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Accept multiple roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        $user = auth()->user();

        // Check if user has any of the required roles
        if (!in_array($user->role, $roles)) {
            $roleList = implode(', ', $roles);
            
            // For API routes, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Unauthorized. You need one of the following roles: {$roleList}"
                ], 403);
            }
            
            // For web routes, redirect with error
            return redirect()->route('dashboard')->with('error', "Unauthorized. You need one of the following roles: {$roleList}");
        }

        return $next($request);
    }

    /**
     * Check if user has minimum role level
     */
    public static function hasMinimumRole(string $userRole, string $requiredRole): bool
    {
        $hierarchy = [
            'user' => 1,
            'manager' => 2,
            'admin' => 3,
        ];

        return ($hierarchy[$userRole] ?? 0) >= ($hierarchy[$requiredRole] ?? 0);
    }
}