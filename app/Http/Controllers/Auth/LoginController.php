<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Auth\User;
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
        $this->middleware('guest')->except(['logout', 'forceLogout', 'refreshToken']);
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

        // Check if too many login attempts
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // 🔥 NEW: Check user status BEFORE attempting login
        $loginField = $request->input('username');
        $user = $this->findUserByLoginField($loginField);

        if ($user && !$user->isActive()) {
            Log::warning('Blocked user attempted login', [
                'user_id' => $user->id,
                'email' => $user->email,
                'username' => $user->username,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            $this->incrementLoginAttempts($request);

            return redirect()->back()
                ->withInput($request->except('password'))
                ->withErrors(['username' => 'Your account has been blocked. Please contact your administrator.']);
        }

        // Attempt to log the user in
        if ($this->attemptLogin($request)) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);

            $authenticatedUser = Auth::user();

            // 🔥 NEW: Update last login time using our enhanced method
            $authenticatedUser->updateLastLogin();

            // 🔥 NEW: Log successful login
            Log::info('User logged in successfully', [
                'user_id' => $authenticatedUser->id,
                'email' => $authenticatedUser->email,
                'username' => $authenticatedUser->username,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // 🔥 NEW: Check if user must change password
            // if ($authenticatedUser->mustChangePassword()) {
            //     return redirect()->route('password.change.form')
            //         ->with('warning', 'You must change your password before continuing.');
            // }

            return $this->sendLoginResponse($request);
        }

        // If login was unsuccessful, increment login attempts
        $this->incrementLoginAttempts($request);

        // 🔥 NEW: Log failed login attempt
        Log::warning('Failed login attempt', [
            'username_field' => $loginField,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * 🔥 NEW: Find user by username or email
     */
    protected function findUserByLoginField($loginField)
    {
        // First try by username
        $user = User::where('username', $loginField)->first();

        // If not found and looks like email, try email field
        if (!$user && filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginField)->first();
        }

        return $user;
    }

    /**
     * Validate the user login request.
     */
    protected function validateLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username or email is required.',
            'password.required' => 'Password is required.',
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
        $remember = $request->boolean('remember');

        return Auth::attempt($credentials, $remember);
    }

    /**
     * 🔥 FIXED: Get the needed authorization credentials from the request.
     */
    protected function credentials(Request $request)
    {
        $loginField = $request->input('username');

        // Check if the input is an email or username
        if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            // Login with email
            return [
                'email' => $loginField,
                'password' => $request->input('password'),
            ];
        } else {
            // Login with username
            return [
                'username' => $loginField,
                'password' => $request->input('password'),
            ];
        }
    }

    /**
     * Send the response after the user was authenticated.
     */
    protected function sendLoginResponse(Request $request)
    {
        $user = Auth::user();

        // 🔥 NEW: Better welcome message with account info
        $welcomeMessage = "Welcome back, {$user->name}!";

        if ($user->employee) {
            $welcomeMessage .= " ({$user->employee->employee_id})";
        }

        return redirect()->intended($this->redirectPath())
            ->with('success', $welcomeMessage);
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
        Log::warning('User lockout triggered', [
            'username' => $request->input('username'),
            'ip' => $request->ip(),
            'attempts' => cache()->get($this->throttleKey($request), 0)
        ]);
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
     * 🔥 ENHANCED: Logout with better error handling and logging
     */
    public function logout(Request $request)
    {
        try {
            // Get user before logout for logging
            $user = auth()->user();

            // Log the logout attempt
            if ($user) {
                Log::info('Logout attempt', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'username' => $user->username,
                    'ip' => $request->ip()
                ]);
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
            if ($user) {
                Log::info('User logged out successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }

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
            Log::error('Logout error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

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

            return redirect('/login')->with('info', 'Session ended.');
        }
    }

    /**
     * Alternative logout method that bypasses CSRF
     */
    public function forceLogout(Request $request)
    {
        // This method can be used as a GET route if CSRF is problematic
        $user = auth()->user();

        if ($user) {
            Log::info('Force logout triggered', [
                'user_id' => $user->id,
                'ip' => $request->ip()
            ]);
        }

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

    /**
     * 🔥 NEW: Check user account status (AJAX endpoint)
     */
    public function checkUserStatus(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        $user = auth()->user();

        // Check if user account is still active
        if (!$user->isActive()) {
            // Force logout blocked user
            Auth::logout();
            $request->session()->invalidate();

            return response()->json([
                'success' => false,
                'message' => 'Account has been blocked',
                'action' => 'logout'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'is_active' => $user->isActive(),
                'must_change_password' => $user->mustChangePassword()
            ]
        ]);
    }
}
