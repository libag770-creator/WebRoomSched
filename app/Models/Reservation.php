<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Schedule;

class Reservation extends Model
{
    protected $fillable = [
        'room_id',
        'user_id',
        'schedule_id',
        'date',
        'day',
        'time',
        'start_time',
        'end_time',
        'purpose',
        'status'
    ];

 public function room()
{
    return $this->belongsTo(Room::class, 'room_id');
}

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
public function schedule()
{
    return $this->belongsTo(
        Schedule::class,
        'schedule_id'
    );
}
}