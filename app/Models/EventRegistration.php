<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'member_id',
        'registered_at',
        'status',
        'payment_status',
        'payment_invoice_id',
        'notes',
        'registered_by_user_id',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(EventCertificate::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'payment_invoice_id');
    }
}
