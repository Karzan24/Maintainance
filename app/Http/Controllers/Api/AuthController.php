<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // <-- CRITICAL FIX: Missing User Model Import
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle client registration (Sets role to 'client' and saves phone_number).
     */
    public function register(Request $request)
    {
        // 1. Validation for client-specific fields
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|unique:users', // Unique phone validation
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Create the user record (Role is set to 'client')
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 'client', // CRITICAL: Mobile registration sets role to 'client'
        ]);

        // 3. Issue a Sanctum token for the client
        $token = $user->createToken('flutter-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'user' => $user,
            'token' => $token,
        ], 201); // 201 Created
    }

    /**
     * Handle client login (issues Sanctum token).
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // This line requires the User model to be imported.
        $user = User::where('email', $request->email)->first();

        // 1. Check credentials
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }
        
        // 2. CRITICAL CHECK: Only allow clients (mobile users) to log in via this API.
        if ($user->role !== 'client') {
            throw ValidationException::withMessages([
                'email' => ['Access denied. Please use the web portal.'],
            ]);
        }

        // 3. Issue a new token
        $token = $user->createToken('flutter-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'user' => $user,
            'token' => $token,
        ]);
    }
    
    /**
     * Handle client logout (revoke token).
     */
    public function logout(Request $request)
    {
        // Delete the current token being used
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Successfully logged out. Token revoked.'], 200);
    }
}