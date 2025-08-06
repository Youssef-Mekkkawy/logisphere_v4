<?php

// File: app/Http/Controllers/Auth/AuthenticatedSessionController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Get user before authentication to check status
        $user = \App\Models\User::where('email', $request->email)
            ->orWhere('username', $request->email)
            ->first();

        // Check if user exists and is blocked before attempting login
        if ($user && !$user->isActive()) {
            Log::warning('Blocked user attempted login', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return back()->withErrors([
                'email' => 'Your account has been blocked. Please contact your administrator.',
            ])->onlyInput('email');
        }

        // Authenticate the user
        $request->authenticate();

        $request->session()->regenerate();

        $authenticatedUser = Auth::user();

        // Update last login timestamp
        $authenticatedUser->updateLastLogin();

        // Log successful login
        Log::info('User logged in successfully', [
            'user_id' => $authenticatedUser->id,
            'email' => $authenticatedUser->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Check if user must change password
        if ($authenticatedUser->mustChangePassword()) {
            return redirect()->route('password.change.form')
                ->with('warning', 'You must change your password before continuing.');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Log logout
        if ($user) {
            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip()
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
