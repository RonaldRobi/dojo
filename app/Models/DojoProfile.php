<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DojoProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'dojo_id',
        'description',
        'history',
        'mission',
        'vision',
        'achievements',
        'gallery_images',
        'social_media_links',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'social_media_links' => 'array',
    ];

    public function dojo(): BelongsTo
    {
        return $this->belongsTo(Dojo::class);
    }
}
