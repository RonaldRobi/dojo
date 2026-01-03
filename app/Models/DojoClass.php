<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DojoClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'dojo_id',
        'name',
        'description',
        'level_min',
        'level_max',
        'age_min',
        'age_max',
        'style',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level_min' => 'integer',
        'level_max' => 'integer',
        'age_min' => 'integer',
        'age_max' => 'integer',
        'capacity' => 'integer',
    ];

    public function dojo()
    {
        return $this->belongsTo(Dojo::class);
    }

    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class, 'class_id');
    }

    public function enrollments()
    {
        return $this->hasManyThrough(ClassEnrollment::class, ClassSchedule::class, 'class_id', 'class_schedule_id');
    }
}

