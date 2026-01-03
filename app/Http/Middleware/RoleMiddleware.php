<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        // Super admin has access to everything
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        $dojoId = $request->input('current_dojo_id') ?? $user->dojo_id;

        // Check if user has any of the required roles for the dojo
        $hasRole = false;
        foreach ($roles as $role) {
            if ($user->hasRole($role, $dojoId)) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            abort(403, 'You do not have the required role');
        }

        return $next($request);
    }
}
