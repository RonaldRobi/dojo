<?php

if (!function_exists('hasRole')) {
    function hasRole(string $role, ?int $dojoId = null): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->hasRole($role, $dojoId);
    }
}

if (!function_exists('hasAnyRole')) {
    function hasAnyRole(array $roles, ?int $dojoId = null): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->hasAnyRole($roles, $dojoId);
    }
}

if (!function_exists('can')) {
    function can(string $resource, string $action, ?int $dojoId = null): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->hasPermission($resource, $action, $dojoId);
    }
}

if (!function_exists('currentDojo')) {
    function currentDojo(): ?int
    {
        if (!auth()->check()) {
            return null;
        }

        $user = auth()->user();
        
        // Super admin might not have a dojo_id
        if ($user->dojo_id) {
            return $user->dojo_id;
        }

        // Try to get dojo from session or first available
        $dojoId = session('current_dojo_id');
        if ($dojoId) {
            return $dojoId;
        }

        // For super admin, return first dojo if available
        if ($user->hasRole('super_admin')) {
            $firstDojo = \App\Models\Dojo::first();
            return $firstDojo ? $firstDojo->id : null;
        }

        return null;
    }
}

if (!function_exists('isActive')) {
    function isActive(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->isActive();
    }
}

if (!function_exists('isSuspended')) {
    function isSuspended(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->isSuspended();
    }
}
