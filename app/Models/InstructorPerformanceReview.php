<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorPerformanceReview extends Model
{
    use HasFactory;

    protected $table = 'instructor_performance_reviews';

    protected $fillable = [
        'instructor_id',
        'reviewed_by_user_id',
        'review_date',
        'rating',
        'feedback',
        'attendance_score',
        'student_feedback_score',
    ];

    protected $casts = [
        'review_date' => 'date',
        'rating' => 'integer',
        'attendance_score' => 'decimal:2',
        'student_feedback_score' => 'decimal:2',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }
}

