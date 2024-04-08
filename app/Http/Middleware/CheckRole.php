<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (! $request->user() || ! $request->user()->roles()->whereIn('name', $roles)->exists()) {
            abort(403, 'Unauthorized action.');
        }
    
        return $next($request);
    }
}
