<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'dojo_id',
        'class_name',
        'class_type',
        'class_id', // Keep for backward compatibility, but nullable
        'instructor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'location',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'day_of_week' => 'integer',
    ];

    public function dojo(): BelongsTo
    {
        return $this->belongsTo(Dojo::class);
    }

    public function dojoClass(): BelongsTo
    {
        return $this->belongsTo(DojoClass::class, 'class_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(ClassEnrollment::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
