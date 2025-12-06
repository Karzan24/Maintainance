<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\MaintenanceRequestController;



Route::get('/test-api', function () {
    return 'API working';
});



// --- PUBLIC API ROUTES (Authentication) ---
// These routes are not protected by a token. They are used to get a token.
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// --- PROTECTED API ROUTES (Requires Sanctum Token) ---
// The 'auth:sanctum' middleware ensures a valid token is present in the Authorization header.
Route::middleware('auth:sanctum')->group(function () {
    
    // API endpoint for submitting a request from the Flutter app
    Route::post('/requests', [MaintenanceRequestController::class, 'apiStore']);
    
    // API endpoint for retrieving the client's requests <-- NEWLY ADDED
    // This calls the apiIndex method in your MaintenanceRequestController.
    Route::get('/requests', [MaintenanceRequestController::class, 'apiIndex']);
    
    // Client specific endpoints
    
    // Get the currently authenticated client's user data
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Client logout (revokes the current API token)
    Route::post('/logout', [AuthController::class, 'logout']);
});