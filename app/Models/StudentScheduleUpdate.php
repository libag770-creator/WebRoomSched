<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentScheduleUpdate extends Model
{
    protected $fillable = [
        'academic_term_id',
        'department_id',
        'program',
        'year_level',
        'schedule_id',
        'event_type',
        'title',
        'message',
        'payload',
        'effective_at',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'payload' => 'array',
        'effective_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
