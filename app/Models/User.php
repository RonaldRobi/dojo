<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'dojo_id',
        'status',
        'password_changed_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_changed_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    // Relationships
    public function dojo(): BelongsTo
    {
        return $this->belongsTo(Dojo::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withPivot('dojo_id', 'assigned_at', 'assigned_by_user_id')
            ->withTimestamps();
    }

    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    public function parentStudentLinks(): HasMany
    {
        return $this->hasMany(ParentStudent::class, 'parent_user_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'parent_students', 'parent_user_id', 'member_id')
            ->withPivot('dojo_id', 'linked_at', 'linked_by_user_id')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeForDojo($query, $dojoId)
    {
        return $query->where('dojo_id', $dojoId);
    }

    // Helper Methods
    public function hasRole($role, $dojoId = null): bool
    {
        $query = $this->roles();
        
        if (is_string($role)) {
            $query->where('name', $role);
        } else {
            $query->where('roles.id', $role);
        }

        if ($dojoId) {
            $query->wherePivot('dojo_id', $dojoId);
        }

        return $query->exists();
    }

    public function hasAnyRole(array $roles, $dojoId = null): bool
    {
        $query = $this->roles()->whereIn('name', $roles);
        
        if ($dojoId) {
            $query->wherePivot('dojo_id', $dojoId);
        }

        return $query->exists();
    }

    public function hasPermission($resource, $action, $dojoId = null): bool
    {
        $permissionService = app(\App\Services\PermissionService::class);
        return $permissionService->can($this, $resource, $action, $dojoId);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }
}
