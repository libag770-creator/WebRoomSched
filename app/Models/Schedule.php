<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoomSwapRequest;

class Schedule extends Model
{
    protected $fillable = [
        'room_id',
        'faculty_id',
        'department_id',

        // Schedule information
        'semester',
        'academic_year',

        'day',
        'time',

        'course_code',
'subject',
'year_level',
'major',
'subject_type',

        'description',
        'color',
    ];


    /*
    |--------------------------------------------------------------------------
    | ROOM
    |--------------------------------------------------------------------------
    */

    public function room()
    {
        return $this->belongsTo(
            Room::class,
            'room_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FACULTY
    |--------------------------------------------------------------------------
    */

    public function faculty()
    {
        return $this->belongsTo(
            User::class,
            'faculty_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DEPARTMENT
    |--------------------------------------------------------------------------
    */

    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'department_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ROOM SWAP REQUESTS
    |--------------------------------------------------------------------------
    */

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