<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleOverride extends Model
{
    protected $fillable = [
        'schedule_id',
        'room_id',
        'faculty_id',
        'created_by',
        'override_date',
        'start_time',
        'end_time',
        'reason',
        'occupied_by',
        'details',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'override_date' => 'date',
        'expires_at' => 'datetime',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
