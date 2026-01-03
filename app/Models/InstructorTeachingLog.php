<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorTeachingLog extends Model
{
    use HasFactory;

    protected $table = 'instructor_teaching_logs';

    protected $fillable = [
        'instructor_id',
        'class_schedule_id',
        'date',
        'hours_taught',
        'attendance_count',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'hours_taught' => 'decimal:2',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class);
    }
}

