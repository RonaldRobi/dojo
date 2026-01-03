<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Dojo;
use App\Services\RoleService;
use App\Services\AuditService;
use Illuminate\Http\Request;

class RoleManagementController extends Controller
{
    protected $roleService;
    protected $auditService;

    public function __construct(RoleService $roleService, AuditService $auditService)
    {
        $this->roleService = $roleService;
        $this->auditService = $auditService;
    }

    public function assignRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|string|exists:roles,name',
            'dojo_id' => 'required|exists:dojos,id',
        ]);

        $role = Role::where('name', $validated['role'])->first();
        $dojo = Dojo::findOrFail($validated['dojo_id']);

        $this->roleService->assignRole($user, $role, $dojo, auth()->user());

        $this->auditService->log('assign_role', $user, [
            'role' => $validated['role'],
            'dojo_id' => $validated['dojo_id'],
        ]);

        return response()->json(['message' => 'Role assigned successfully']);
    }

    public function removeRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|string|exists:roles,name',
            'dojo_id' => 'required|exists:dojos,id',
        ]);

        $role = Role::where('name', $validated['role'])->first();
        $dojo = Dojo::findOrFail($validated['dojo_id']);

        $this->roleService->removeRole($user, $role, $dojo);

        $this->auditService->log('remove_role', $user, [
            'role' => $validated['role'],
            'dojo_id' => $validated['dojo_id'],
        ]);

        return response()->json(['message' => 'Role removed successfully']);
    }

    public function getUserRoles(User $user, Dojo $dojo = null)
    {
        if ($dojo) {
            $roles = $this->roleService->getUserRolesForDojo($user, $dojo);
        } else {
            $roles = $user->roles()->with('dojo')->get();
        }

        return response()->json($roles);
    }
}
