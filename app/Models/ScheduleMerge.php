<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleMerge extends Model
{
    protected $fillable = [
        'schedule_id',
        'department_id',
        'faculty_subject_id',
        'status',
        'approved_by',
        'approved_at',
    ];


    /*
    |--------------------------------------------------------------------------
    | ORIGINAL / OWNER SCHEDULE
    |--------------------------------------------------------------------------
    */

    public function schedule()
    {
        return $this->belongsTo(
            Schedule::class,
            'schedule_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DEPARTMENT REQUESTING THE MERGE
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
    | SUBJECT REQUESTING THE MERGE
    |--------------------------------------------------------------------------
    */

    public function facultySubject()
    {
        return $this->belongsTo(
            FacultySubject::class,
            'faculty_subject_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | USER WHO APPROVED THE MERGE
    |--------------------------------------------------------------------------
    */

    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}