<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
