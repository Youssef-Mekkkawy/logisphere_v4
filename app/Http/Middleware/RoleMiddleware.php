<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Accept multiple roles
     */

    public function handle(Request $request, Closure $next, ...$roles)
    {
        // if (!Auth::check()) {
        //     return redirect()->route('login');
        // }

        // $user = Auth::user();
        // // dd($user->hasRole);
        // // Check if user has any of the required roles
        // foreach ($roles as $role) {
        //     if ($user->hasRole($role)) {
        //         return $next($request);
        //     }
        // }

        // // If no roles match, deny access
        // abort(403, 'Access denied. You do not have the required role.');
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
