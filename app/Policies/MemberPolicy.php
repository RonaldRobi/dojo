<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Member;

class MemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'owner', 'finance', 'coach', 'student', 'parent']);
    }

    public function view(User $user, Member $member): bool
    {
        // Super Admin and Owner can view all
        if ($user->hasAnyRole(['super_admin', 'owner'])) {
            return true;
        }

        // Student can only view own profile
        if ($user->hasRole('student')) {
            return $member->user_id === $user->id;
        }

        // Parent can view linked children
        if ($user->hasRole('parent')) {
            return $member->parents()->where('users.id', $user->id)->exists();
        }

        // Finance and Coach can view all in their dojo
        return $user->hasAnyRole(['finance', 'coach']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'owner']);
    }

    public function update(User $user, Member $member): bool
    {
        // Super Admin and Owner can update all
        if ($user->hasAnyRole(['super_admin', 'owner'])) {
            return true;
        }

        // Student can update own profile
        if ($user->hasRole('student')) {
            return $member->user_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, Member $member): bool
    {
        // Only Super Admin and Owner can delete
        return $user->hasAnyRole(['super_admin', 'owner']);
    }
}
