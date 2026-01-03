<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'dojo_id',
        'title',
        'description',
        'image_path',
        'achieved_date',
        'display_order',
    ];

    protected $casts = [
        'achieved_date' => 'date',
        'display_order' => 'integer',
    ];

    public function dojo(): BelongsTo
    {
        return $this->belongsTo(Dojo::class);
    }
}
