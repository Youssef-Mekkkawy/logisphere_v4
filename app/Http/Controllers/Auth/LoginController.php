<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        // Validate the login request
        $this->validateLogin($request);
        $credentials = $request->only('username', 'password');

        // dd($credentials);
        // Check if too many login attempts
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        // dd($request);
        // Attempt to log the user in
        if ($this->attemptLogin($request)) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            // dd('test');
            // Update last login time
            Auth::user()->update(['last_login' => now()]);

            return $this->sendLoginResponse($request);
        }

        // If login was unsuccessful, increment login attempts
        $this->incrementLoginAttempts($request);
        // dd('final');
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     */
    protected function validateLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }
    }

    /**
     * Attempt to log the user into the application.
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        // dd($credentials);
        $remember = $request->boolean('remember');
        // dd($remember);
        // dd(Auth::attempt($credentials, $remember));
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Get the needed authorization credentials from the request.
     */
    protected function credentials(Request $request)
    {
        $username = $request->input('username');

        // Check if the input is an email or username
        $field = filter_var($username) ? 'username' : 'username';

        return [
            'username' => $username,
            'password' => $request->input('password'),
        ];
    }

    /**
     * Send the response after the user was authenticated.
     */
    protected function sendLoginResponse(Request $request)
    {
        $user = Auth::user();

        return redirect()->intended($this->redirectPath())
            ->with('success', "Welcome back, {$user->name}!");
    }

    /**
     * Send the response after a failed login attempt.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->except('password'))
            ->withErrors(['username' => 'These credentials do not match our records.']);
    }

    /**
     * Get the post-login redirect path.
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/dashboard';
    }

    /**
     * Log the user out of the application.
     */


    /**
     * Determine if the user has too many failed login attempts.
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        $maxAttempts = 5; // Maximum login attempts
        $decayMinutes = 1; // Lockout duration in minutes

        $key = $this->throttleKey($request);

        return cache()->has($key) && cache()->get($key) >= $maxAttempts;
    }

    /**
     * Increment the login attempts for the user.
     */
    protected function incrementLoginAttempts(Request $request)
    {
        $key = $this->throttleKey($request);
        $attempts = cache()->get($key, 0) + 1;

        cache()->put($key, $attempts, now()->addMinutes(1));
    }

    /**
     * Clear the login locks for the given user credentials.
     */
    protected function clearLoginAttempts(Request $request)
    {
        cache()->forget($this->throttleKey($request));
    }

    /**
     * Fire an event when a lockout occurs.
     */
    protected function fireLockoutEvent(Request $request)
    {
        // You can fire events here if needed
    }

    /**
     * Redirect the user after determining they are locked out.
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = 60; // Lockout duration in seconds

        return redirect()->back()
            ->withInput($request->except('password'))
            ->withErrors(['username' => "Too many login attempts. Please try again in {$seconds} seconds."]);
    }

    /**
     * Get the throttle key for the given request.
     */
    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('username')) . '|' . $request->ip();
    }

    /**
     * Enhanced logout with better error handling
     */
    public function logout(Request $request)
    {
        try {
            // Log the logout attempt
            Log::info('Logout attempt for user: ' . (auth()->user()->id ?? 'guest'));

            // Get user before logout for logging
            $user = auth()->user();

            // Update last_login if you're tracking it
            if ($user) {
                $user->update(['last_login' => now()]);
            }

            // Perform logout
            Auth::logout();

            // Invalidate session
            $request->session()->invalidate();

            // Regenerate CSRF token
            $request->session()->regenerateToken();

            // Clear any additional session data
            Session::flush();

            // Log successful logout
            Log::info('User logged out successfully: ' . ($user->id ?? 'unknown'));

            // Check if it's an AJAX request
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'You have been logged out successfully.',
                    'redirect' => route('login')
                ]);
            }

            // Regular redirect
            return redirect('/login')->with('success', 'You have been logged out successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Logout error: ' . $e->getMessage());

            // Force logout even if there's an error
            Auth::logout();

            // Clear everything
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out.',
                    'redirect' => route('login')
                ]);
            }

            return redirect('/login');
        }
    }

    /**
     * Alternative logout method that bypasses CSRF
     */
    public function forceLogout(Request $request)
    {
        // This method can be used as a GET route if CSRF is problematic
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('info', 'Session expired. Please log in again.');
    }

    /**
     * Refresh CSRF token (AJAX endpoint)
     */
    public function refreshToken(Request $request)
    {
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'token' => csrf_token()
        ]);
    }
}
