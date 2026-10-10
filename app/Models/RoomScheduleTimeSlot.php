<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomScheduleTimeSlot extends Model
{
    use HasFactory;

    protected $table = 'room_schedule_time_slots';

    protected $fillable = [
        'room_id',
        'academic_term_id',
        'day',
        'start_time',
        'end_time',
        'row_position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerm::class);
    }
}