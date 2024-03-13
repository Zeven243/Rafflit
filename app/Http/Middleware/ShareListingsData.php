<?php

namespace App\Http\Middleware;

use Closure;
use Inertia\Inertia;
use App\Models\Listings;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShareListingsData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        Inertia::share('listings', function () {
            return Listings::all();
        });
    
        return $next($request);
    }
}
