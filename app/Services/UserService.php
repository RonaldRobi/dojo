<?php

namespace App\Services;

use App\Models\User;
use App\Models\Dojo;
use App\Services\RoleService;
use App\Services\AuditService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected $roleService;
    protected $auditService;

    public function __construct(RoleService $roleService, AuditService $auditService)
    {
        $this->roleService = $roleService;
        $this->auditService = $auditService;
    }

    public function create(array $data, ?User $assignedBy = null): User
    {
        return DB::transaction(function () use ($data, $assignedBy) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'dojo_id' => $data['dojo_id'] ?? null,
                'status' => $data['status'] ?? 'active',
                'password_changed_at' => now(),
            ]);

            // Assign roles if provided
            if (isset($data['roles']) && is_array($data['roles'])) {
                foreach ($data['roles'] as $roleData) {
                    $dojoId = $roleData['dojo_id'] ?? $data['dojo_id'] ?? null;
                    if ($dojoId && isset($roleData['role'])) {
                        $this->roleService->assignRole($user, $roleData['role'], $dojoId, $assignedBy);
                    }
                }
            }

            $this->auditService->logCreate($user, $data);

            return $user->fresh();
        });
    }

    public function update(User $user, array $data, ?User $updatedBy = null): User
    {
        return DB::transaction(function () use ($user, $data, $updatedBy) {
            $oldAttributes = $user->toArray();

            $user->update([
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
                'dojo_id' => $data['dojo_id'] ?? $user->dojo_id,
                'status' => $data['status'] ?? $user->status,
            ]);

            if (isset($data['password'])) {
                $user->update([
                    'password' => Hash::make($data['password']),
                    'password_changed_at' => now(),
                ]);
            }

            $this->auditService->logUpdate($user, $oldAttributes, $user->toArray());

            return $user->fresh();
        });
    }

    public function suspend(User $user, ?User $suspendedBy = null): User
    {
        $user->update(['status' => 'suspended']);
        $this->auditService->log('suspend', $user, ['suspended_by' => $suspendedBy?->id]);
        return $user->fresh();
    }

    public function activate(User $user, ?User $activatedBy = null): User
    {
        $user->update(['status' => 'active']);
        $this->auditService->log('activate', $user, ['activated_by' => $activatedBy?->id]);
        return $user->fresh();
    }

    public function updateLastLogin(User $user): void
    {
        $user->update(['last_login_at' => now()]);
    }
}

