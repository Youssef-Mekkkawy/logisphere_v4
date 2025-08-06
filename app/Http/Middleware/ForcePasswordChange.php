<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip middleware for guests
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // 🔥 NEW: Check if user has been marked for forced logout
        if (cache()->has("force_logout_user_{$user->id}")) {
            cache()->forget("force_logout_user_{$user->id}");

            Log::info('User force logged out via cache flag', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('warning', 'You have been logged out by an administrator.');
        }

        // Check if user account is blocked
        if (!$user->isActive()) {
            Log::info('Blocked user auto-logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'route' => $request->route()->getName()
            ]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Your account has been blocked. Please contact administrator.');
        }

        // Check if user must change password
        if ($user->mustChangePassword()) {
            // Allow access to password change routes and logout
            $allowedRoutes = [
                'password.change',
                'password.update',
                'logout',
                'password.change.form',
                'force-logout' // Allow logout even when password change required
            ];

            $currentRoute = $request->route()->getName();

            // If not on allowed route, redirect to password change
            if (!in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('password.change.form')
                    ->with('warning', 'You must change your password before continuing.');
            }
        }

        return $next($request);
    }
}
