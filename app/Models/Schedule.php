<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoomSwapRequest;

class Schedule extends Model
{
   protected $fillable = [
    'room_id',
    'day',
    'time',
    'course_code',
    'subject',
    'instructor',
    'description',
    'color'
];
    public function room()
{
    return $this->belongsTo(Room::class);
}

public function requesterRoomSwapRequests()
{
    return $this->hasMany(
        RoomSwapRequest::class,
        'requester_schedule_id'
    );
}

public function targetRoomSwapRequests()
{
    return $this->hasMany(
        RoomSwapRequest::class,
        'target_schedule_id'
    );
}
}
