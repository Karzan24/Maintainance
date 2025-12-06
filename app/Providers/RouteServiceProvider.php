<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Define rate limits for the API (60 requests per minute per user/IP)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // CRITICAL FIX: Loads the routes/api.php file with the '/api' prefix
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // CRITICAL FIX: Loads the routes/web.php file
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        // 
    }
}