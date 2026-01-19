<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'dojo_id',
        'user_id',
        'name',
        'birth_date',
        'gender',
        'phone',
        'address',
        'status',
        'join_date',
        'current_level',
        'current_belt_id',
        'style',
        'medical_notes',
        'waiver_signed_at',
        'waiver_document_path',
        'qr_code',
        'qr_code_expires_at',
        'profile_photo',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
        'waiver_signed_at' => 'datetime',
        'qr_code_expires_at' => 'datetime',
    ];

    public function dojo(): BelongsTo
    {
        return $this->belongsTo(Dojo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'parent_students', 'member_id', 'parent_user_id')
            ->withPivot('dojo_id', 'linked_at', 'linked_by_user_id')
            ->withTimestamps();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(ClassEnrollment::class);
    }

    public function currentBelt(): BelongsTo
    {
        return $this->belongsTo(Rank::class, 'current_belt_id');
    }

    public function ranks(): HasMany
    {
        return $this->hasMany(MemberRank::class);
    }

    public function gradingResults(): HasMany
    {
        return $this->hasMany(GradingResult::class);
    }

    public function progressLogs(): HasMany
    {
        return $this->hasMany(ProgressLog::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
