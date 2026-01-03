<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDojoAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        // Super Admin (Head Office) can access all dojos/branches
        // Since this is an internal multi-branch system, Head Office has unrestricted access
        if ($user->hasRole('super_admin')) {
            // If dojo_id is specified, store it for context; otherwise allow access to all
            $dojoId = $request->route('dojo') ?? $request->input('dojo_id');
            if ($dojoId) {
                $request->merge(['current_dojo_id' => $dojoId]);
            }
            return $next($request);
        }

        // Get dojo_id from route parameter or request
        $dojoId = $request->route('dojo') ?? $request->input('dojo_id') ?? $user->dojo_id;

        if (!$dojoId) {
            abort(403, 'Dojo access required');
        }

        // Check if user has any role for this dojo
        if (!$user->roles()->wherePivot('dojo_id', $dojoId)->exists()) {
            abort(403, 'You do not have access to this dojo');
        }

        // Store dojo_id in request for later use
        $request->merge(['current_dojo_id' => $dojoId]);

        return $next($request);
    }
}
