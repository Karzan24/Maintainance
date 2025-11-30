<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController; 
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\ProfileController; // <-- NEW IMPORT

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PUBLIC ROUTES (No authentication required) ---

// Login Screen (GET / and GET /login)
Route::get('/', function () { return view('welcome'); })->name('welcome');
Route::get('/login', function () { return view('welcome'); })->name('login');

// Login Submission Handler (POST /login)
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');


// --- REGISTRATION ROUTES ---
Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest')->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');


// --- AUTHENTICATED ROUTES (User must be logged in) ---
Route::middleware('auth')->group(function () {
    
    // 1. ADMIN DASHBOARD ROUTE
    Route::get('/dashboard', [MaintenanceRequestController::class, 'index'])->name('dashboard');

    // 2. USER PROFILE ROUTES (Combined Account Info + My Requests) <-- UPDATED
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); 
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); 
    
    // 3. LOGOUT ROUTE
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // 4. MAINTENANCE REQUEST ROUTES (CRUD)
    Route::resource('requests', MaintenanceRequestController::class)->only([
        'create', 'store', 'destroy'
    ]);

    // 5. CUSTOM STATUS/ACTION ROUTES
    Route::post('/requests/{maintenanceRequest}/status', [MaintenanceRequestController::class, 'updateStatus'])
        ->name('requests.update_status');
    Route::post('/requests/{maintenanceRequest}/complete', [MaintenanceRequestController::class, 'clientComplete'])
        ->name('requests.client_complete');
});