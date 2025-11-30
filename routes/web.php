<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MaintenanceRequestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PUBLIC ROUTES (No authentication required) ---

// Login Screen (GET / and GET /login)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', function () {
    return view('welcome'); 
})->name('login');

// Login Form Submission Handler (POST /login)
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');


// --- AUTHENTICATED ROUTES (User must be logged in) ---
Route::middleware('auth')->group(function () {
    
    // 1. ADMIN DASHBOARD ROUTE (All requests)
    Route::get('/dashboard', [MaintenanceRequestController::class, 'index'])->name('dashboard');

    // 2. CLIENT VIEW ROUTE (Only my requests)
    Route::get('/my-requests', [MaintenanceRequestController::class, 'clientIndex'])->name('my_requests');

    // 3. LOGOUT ROUTE
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // 4. MAINTENANCE REQUEST ROUTES (Resource for CRUD)
    // CRITICAL FIX: Explicitly include 'create', 'store', and 'destroy' to guarantee the routes exist.
    Route::resource('requests', MaintenanceRequestController::class)->only([
        'create', 'store', 'destroy'
    ]);

    // 5. CUSTOM ROUTE FOR ADMIN STATUS UPDATES (Accept/Complete)
    Route::post('/requests/{maintenanceRequest}/status', [MaintenanceRequestController::class, 'updateStatus'])
        ->name('requests.update_status');

    // 6. CUSTOM ROUTE FOR CLIENT COMPLETION
    Route::post('/requests/{maintenanceRequest}/complete', [MaintenanceRequestController::class, 'clientComplete'])
        ->name('requests.client_complete');

       Route::delete('/requests/{maintenanceRequest}', [MaintenanceRequestController::class, 'destroy'])
        ->name('requests.destroy.explicit');
});