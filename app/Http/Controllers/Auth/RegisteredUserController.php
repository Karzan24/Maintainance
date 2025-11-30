<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // CRITICAL: Ensure User Model is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        // Loads the view file at: resources/views/register.blade.php
        return view('register'); 
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'confirmed' ensures 'password' matches 'password_confirmation'
            'password' => ['required', 'confirmed', Rules\Password::defaults()], 
        ]);

        // 2. Create the user record in the MySQL database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Log the newly created user in immediately
        Auth::login($user);

        // 4. Redirect the user to the dashboard
        return redirect(route('dashboard'));
    }
}