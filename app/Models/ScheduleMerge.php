<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleMerge extends Model
{
    protected $fillable = [
        'schedule_id',
        'department_id',
        'requested_by',
        'faculty_subject_id',
        'subject_id',
        'request_group_id',
        'status',
        'share_type',
        'approved_by',
        'approved_at',
        'decision_note',
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

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
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
