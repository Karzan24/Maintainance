<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;
use App\Models\MaintenanceRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile information and their maintenance requests.
     */
    public function edit()
    {
        $user = Auth::user(); 

        // Guard clause for session safety
        if (!$user) {
            Auth::logout();
            return redirect('/login')->with('error', 'Authentication session expired. Please log in again.');
        }

        // Fetch only requests associated with the logged-in user
        $userRequests = $user->maintenanceRequests()
            ->orderBy('created_at', 'desc')
            ->get(); 

        return view('profile', compact('user', 'userRequests'));
    }

    /**
     * Update the user's profile information (Name, Email, Password).
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $isProfileUpdate = $request->has('name') || $request->has('email');
        $isPasswordUpdate = $request->filled('current_password') || $request->filled('new_password');

        // --- 1. Handle Password Update ---
        if ($isPasswordUpdate) {
            
            // Validate all necessary password fields
            // If validation fails, it redirects back with errors automatically.
            $request->validate([
                'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                    if (! Hash::check($value, $user->password)) {
                        $fail('The provided current password does not match your current password.');
                    }
                }],
                'new_password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
            ]);

            // Apply and save the new password
            $user->password = Hash::make($request->input('new_password'));
            $user->save(); 

            // Log user out after successful password change for security
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect('/login')->with('success', 'Password updated successfully! Please log in with your new password.');
        }

        // --- 2. Handle Name/Email Update (Only runs if no password fields were submitted) ---
        if ($isProfileUpdate) {
             // Validation for Name and Email
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], 
            ]);

            $user->fill($request->only('name', 'email'));
            $user->save();
            return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
        }
        
        // Fallback if the request was empty (no update performed)
        return redirect()->route('profile.edit');
    }
}