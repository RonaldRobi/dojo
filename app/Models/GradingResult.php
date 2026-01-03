<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradingResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'rank_id',
        'grading_date',
        'instructor_id',
        'exam_score',
        'recommendation',
        'status',
        'notes',
    ];

    protected $casts = [
        'grading_date' => 'date',
        'exam_score' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }
}

