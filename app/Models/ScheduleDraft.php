<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Room;
use App\Models\User;
use App\Models\Department;

class ScheduleDraft extends Model
{
    protected $fillable = [
        'room_id',
        'faculty_id',
        'department_id',

        // Schedule information
        'semester',
        'academic_year',

        'created_by',

        'day',
        'time',

        'course_code',
        'subject',
        'year_level',

        // Optional: Major / Non-major
        'subject_type',

        'instructor',
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
    | CREATOR
    |--------------------------------------------------------------------------
    */

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
}