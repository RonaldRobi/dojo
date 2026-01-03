<?php

namespace App\Services;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Cache;

class SecurityService
{
    public function detectSuspiciousActivity(User $user, string $action): bool
    {
        $key = "suspicious_activity_{$user->id}";
        $count = Cache::get($key, 0);
        
        // Track failed login attempts or suspicious patterns
        if ($action === 'failed_login') {
            $count++;
            Cache::put($key, $count, now()->addMinutes(15));
            
            if ($count >= 5) {
                $this->logSuspiciousActivity($user, $action, "Multiple failed login attempts: {$count}");
                return true;
            }
        }
        
        return false;
    }

    protected function logSuspiciousActivity(User $user, string $action, string $details): void
    {
        AuditLog::create([
            'user_id' => $user->id,
            'dojo_id' => $user->dojo_id,
            'action' => 'suspicious_activity',
            'model' => User::class,
            'model_id' => $user->id,
            'changes' => [
                'action' => $action,
                'details' => $details,
            ],
        ]);
    }

    public function checkPasswordStrength(string $password): array
    {
        $strength = 0;
        $feedback = [];

        if (strlen($password) >= 8) {
            $strength++;
        } else {
            $feedback[] = 'Password should be at least 8 characters';
        }

        if (preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password)) {
            $strength++;
        } else {
            $feedback[] = 'Password should contain both uppercase and lowercase letters';
        }

        if (preg_match('/[0-9]/', $password)) {
            $strength++;
        } else {
            $feedback[] = 'Password should contain at least one number';
        }

        if (preg_match('/[^a-zA-Z0-9]/', $password)) {
            $strength++;
        } else {
            $feedback[] = 'Password should contain at least one special character';
        }

        return [
            'strength' => $strength,
            'max_strength' => 4,
            'is_strong' => $strength >= 3,
            'feedback' => $feedback,
        ];
    }
}

