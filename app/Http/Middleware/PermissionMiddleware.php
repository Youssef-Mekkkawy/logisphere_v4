<?php

// File: app/Http/Middleware/PermissionMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 🔥 If user is admin, allow everything
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user has any of the required permissions
        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return $next($request);
            }
        }

        // If no permissions match, deny access
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Access denied. You do not have the required permissions.',
                'required_permissions' => $permissions,
                'user_permissions' => $user->getAllPermissions()->pluck('slug')->toArray()
            ], 403);
        }

        abort(403, 'Access denied. You do not have the required permissions.');
    }
}
