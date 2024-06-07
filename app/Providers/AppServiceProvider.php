<?php
namespace App\Providers;

use Illuminate\Support\Facades\View;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

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
        // Force HTTPS for all generated URLs
        URL::forceScheme('https');

        Inertia::share([
            'auth' => function () {
                return [
                    'user' => Auth::user() ? Auth::user()->load('roles') : null,
                ];
            },
        ]);
    }
}
