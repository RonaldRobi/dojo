<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DojoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'dojo_id',
        'key',
        'value',
        'type',
        'description',
    ];

    public function dojo(): BelongsTo
    {
        return $this->belongsTo(Dojo::class);
    }
}
