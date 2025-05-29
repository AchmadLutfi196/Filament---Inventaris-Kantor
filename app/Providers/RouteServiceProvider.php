<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    public function boot(): void
    {
        $this->routes(function () {
            // API Routes
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Web Routes
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // User Frontend Routes
            Route::middleware('web')
                ->name('frontend.')
                ->group(base_path('routes/user.php'));

            // Admin Routes - PASTIKAN INI ADA
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        });
    }
}