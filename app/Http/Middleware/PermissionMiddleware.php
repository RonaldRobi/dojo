<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PermissionService;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $resource
     * @param  string  $action
     */
    public function handle(Request $request, Closure $next, string $resource, string $action): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        $dojoId = $request->input('current_dojo_id') ?? $user->dojo_id;

        if (!$this->permissionService->can($user, $resource, $action, $dojoId)) {
            abort(403, 'You do not have permission to perform this action');
        }

        return $next($request);
    }
}
