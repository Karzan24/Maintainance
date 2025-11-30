<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Basic Welcome/Index Page (Now your Login Screen)
Route::get('/', function () {
    return view('welcome');
});

// --- Authentication Routes (Login) ---

// 1. Route to show the login form (GET request)
Route::get('/login', function () {
    return view('welcome'); 
})->name('login');

// 2. Route to handle the form submission (POST request)
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');

// --- Protected Routes (Authenticated Users) ---

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});