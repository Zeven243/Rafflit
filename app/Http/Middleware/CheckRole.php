<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        // Check if the user is authenticated
        if (! $request->user()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the user has any of the specified roles
        if ($request->user()->hasAnyRole($roles)) {
            return $next($request);
        }

        // Check if the user has the required roles for the existing feature
        if (! $request->user()->roles()->whereIn('name', $roles)->exists()) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
