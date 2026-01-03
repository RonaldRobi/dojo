<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberRank extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'rank_id',
        'achieved_at',
        'awarded_by_instructor_id',
        'certificate_url',
        'grading_event_id',
    ];

    protected $casts = [
        'achieved_at' => 'datetime',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class);
    }

    public function awardedBy(): BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'awarded_by_instructor_id');
    }
}

