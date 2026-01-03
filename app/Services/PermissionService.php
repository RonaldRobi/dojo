<?php

namespace App\Services;

use App\Models\User;

class PermissionService
{
    /**
     * Check if user has permission for a resource and action
     *
     * @param User $user
     * @param string $resource
     * @param string $action
     * @param int|null $dojoId
     * @return bool
     */
    public function can(User $user, string $resource, string $action, ?int $dojoId = null): bool
    {
        // Super Admin has all permissions
        if ($user->hasRole('super_admin')) {
            return true;
        }

        $permissions = config('permissions.permissions', []);

        if (!isset($permissions[$resource])) {
            return false;
        }

        $resourcePermissions = $permissions[$resource];

        // Get user roles for the dojo (or all roles if dojoId is null)
        $userRoles = $dojoId 
            ? $user->roles()->wherePivot('dojo_id', $dojoId)->get()
            : $user->roles()->get();

        foreach ($userRoles as $role) {
            $roleName = strtoupper($this->getRoleCode($role->name));
            
            if (isset($resourcePermissions[$roleName])) {
                $allowedActions = $resourcePermissions[$roleName];
                if (in_array($action, $allowedActions)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Convert role name to role code
     *
     * @param string $roleName
     * @return string
     */
    private function getRoleCode(string $roleName): string
    {
        $mapping = [
            'super_admin' => 'SA',
            'owner' => 'OWN',
            'finance' => 'FIN',
            'coach' => 'COA',
            'student' => 'STU',
            'parent' => 'PAR',
        ];

        return $mapping[$roleName] ?? $roleName;
    }

    /**
     * Check if user has any of the specified roles
     *
     * @param User $user
     * @param array $roles
     * @param int|null $dojoId
     * @return bool
     */
    public function hasAnyRole(User $user, array $roles, ?int $dojoId = null): bool
    {
        $query = $user->roles();

        if ($dojoId) {
            $query->wherePivot('dojo_id', $dojoId);
        }

        return $query->whereIn('name', $roles)->exists();
    }
}

