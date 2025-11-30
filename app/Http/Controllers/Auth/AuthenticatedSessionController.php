<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request (Login logic).
     */
    public function store(Request $request)
    {
        // 1. Validate the login request data (email and password must be present)
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Attempt to authenticate the user using the provided credentials
        // The $request->boolean('remember') handles the "Remember Me" checkbox.
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            // If authentication fails, throw a validation error back to the form
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'), // Laravel's default failure message
            ]);
        }

        // 3. Regenerate the session ID for security
        $request->session()->regenerate();

        // 4. Redirect the user to the intended URL (which we set as 'dashboard')
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session (Logout logic).
     */
    public function destroy(Request $request)
    {
        // Log the user out of the 'web' guard
        Auth::guard('web')->logout();

        // Invalidate the current session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        // Redirect the user back to the welcome/login page
        return redirect('/');
    }
}