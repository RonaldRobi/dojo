<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log an action to audit log
     *
     * @param string $action
     * @param Model $model
     * @param array|null $changes
     * @param int|null $dojoId
     * @return AuditLog
     */
    public function log(string $action, Model $model, ?array $changes = null, ?int $dojoId = null): AuditLog
    {
        $user = Auth::user();

        return AuditLog::create([
            'user_id' => $user?->id,
            'dojo_id' => $dojoId ?? $user?->dojo_id,
            'action' => $action,
            'model' => get_class($model),
            'model_id' => $model->id,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Log create action
     *
     * @param Model $model
     * @param array $attributes
     * @param int|null $dojoId
     * @return AuditLog
     */
    public function logCreate(Model $model, array $attributes = [], ?int $dojoId = null): AuditLog
    {
        return $this->log('create', $model, ['attributes' => $attributes], $dojoId);
    }

    /**
     * Log update action
     *
     * @param Model $model
     * @param array $oldAttributes
     * @param array $newAttributes
     * @param int|null $dojoId
     * @return AuditLog
     */
    public function logUpdate(Model $model, array $oldAttributes, array $newAttributes, ?int $dojoId = null): AuditLog
    {
        return $this->log('update', $model, [
            'old' => $oldAttributes,
            'new' => $newAttributes,
        ], $dojoId);
    }

    /**
     * Log delete action
     *
     * @param Model $model
     * @param array $attributes
     * @param int|null $dojoId
     * @return AuditLog
     */
    public function logDelete(Model $model, array $attributes = [], ?int $dojoId = null): AuditLog
    {
        return $this->log('delete', $model, ['attributes' => $attributes], $dojoId);
    }
}

