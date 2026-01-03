<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'instructor_id',
        'date',
        'notes',
        'skills_improved',
        'areas_to_improve',
        'curriculum_items_completed',
    ];

    protected $casts = [
        'date' => 'date',
        'curriculum_items_completed' => 'array',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }
}

