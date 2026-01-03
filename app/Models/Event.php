<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'dojo_id',
        'name',
        'type',
        'description',
        'event_date',
        'registration_deadline',
        'location',
        'capacity',
        'registration_fee',
        'is_active',
        'is_public',
        'event_image',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'registration_deadline' => 'date',
        'registration_fee' => 'decimal:2',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'capacity' => 'integer',
    ];

    public function dojo(): BelongsTo
    {
        return $this->belongsTo(Dojo::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }
}
