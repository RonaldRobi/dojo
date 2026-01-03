<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\Dojo;

class RoleService
{
    /**
     * Assign role to user for a specific dojo
     *
     * @param User $user
     * @param string|Role $role
     * @param Dojo|int $dojo
     * @param User|null $assignedBy
     * @return void
     */
    public function assignRole(User $user, $role, $dojo, ?User $assignedBy = null): void
    {
        $roleId = $role instanceof Role ? $role->id : Role::where('name', $role)->firstOrFail()->id;
        $dojoId = $dojo instanceof Dojo ? $dojo->id : $dojo;
        $assignedById = $assignedBy?->id;

        $user->roles()->syncWithoutDetaching([
            $roleId => [
                'dojo_id' => $dojoId,
                'assigned_by_user_id' => $assignedById,
                'assigned_at' => now(),
            ],
        ]);
    }

    /**
     * Remove role from user for a specific dojo
     *
     * @param User $user
     * @param string|Role $role
     * @param Dojo|int $dojo
     * @return void
     */
    public function removeRole(User $user, $role, $dojo): void
    {
        $roleId = $role instanceof Role ? $role->id : Role::where('name', $role)->firstOrFail()->id;
        $dojoId = $dojo instanceof Dojo ? $dojo->id : $dojo;

        $user->roles()->wherePivot('dojo_id', $dojoId)->detach($roleId);
    }

    /**
     * Get user roles for a specific dojo
     *
     * @param User $user
     * @param Dojo|int $dojo
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserRolesForDojo(User $user, $dojo)
    {
        $dojoId = $dojo instanceof Dojo ? $dojo->id : $dojo;

        return $user->roles()->wherePivot('dojo_id', $dojoId)->get();
    }
}

