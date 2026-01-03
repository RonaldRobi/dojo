<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_registration_id',
        'certificate_url',
        'issued_at',
        'issued_by_instructor_id',
        'certificate_type',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function eventRegistration(): BelongsTo
    {
        return $this->belongsTo(EventRegistration::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'issued_by_instructor_id');
    }
}

