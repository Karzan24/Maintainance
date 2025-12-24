<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route; // CRITICAL: Import Route Facade
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // --- INJECTED ROUTE LOADING LOGIC FROM RouteServiceProvider ---
        
        // 1. Define rate limits for the API
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // 2. Load API and Web routes directly
        $this->loadRoutes();

        // --- END INJECTED LOGIC ---
    }
    
    // Custom method to encapsulate route loading
    protected function loadRoutes()
    {
        // Loads the routes/api.php file with the '/api' prefix
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        // Loads the routes/web.php file
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}