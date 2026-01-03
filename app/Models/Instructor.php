<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instructor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'dojo_id',
        'user_id',
        'name',
        'email',
        'phone',
        'specialization',
        'bio',
        'hire_date',
        'status',
        'profile_photo',
        'certification_level',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    public function dojo(): BelongsTo
    {
        return $this->belongsTo(Dojo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class);
    }
}
