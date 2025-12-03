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
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 1. Attempt to authenticate the user
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            // If authentication FAILS (bad credentials)
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'), 
            ]);
        }

        // 2. CRITICAL ROLE CHECK: Authentication succeeded, now check the role.
        $user = Auth::user();

        if ($user->role !== 'admin') {
            // If the user is a 'client', immediately log them out.
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Throw a general failure message to hide the actual reason, maintaining security.
            throw ValidationException::withMessages([
                'email' => 'Access denied. Only administrators are allowed to log in via the web portal.', 
            ]);
        }
        
        // 3. User is an Admin. Regenerate the session and grant access.
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session (Logout logic).
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}