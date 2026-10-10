<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'schedules';

    protected $fillable = [

        'academic_term_id',

        'scheduling_mode',

        'semester',

        'academic_year',

        'schedule_date',

        'schedule_start_date',

        'schedule_expiration_date',

        'college_id',

        'building_id',

        'room_id',

        'faculty_id',

        'department_id',

        'department_chair_id',

        'subject',

        'course',

        'course_code',

        'section',

        'day',

        'day_of_week',

        'time',

        'start_time',

        'end_time',

        'row_position',

        'year_level',

        'major',

        'program',

        'subject_type',

        'instructor',

        'color',

        'remarks',

        'status',
    ];

    protected $casts = [

        'academic_term_id' => 'integer',

        'schedule_date' => 'date',

        'schedule_start_date' => 'date',

        'schedule_expiration_date' => 'date',

        'deleted_at' => 'datetime',

        'created_at' => 'datetime',

        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | ACADEMIC TERM
    |--------------------------------------------------------------------------
    */

    public function academicTerm()
    {
        return $this->belongsTo(
            AcademicTerm::class,
            'academic_term_id'
        );
    }

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
    | BUILDING
    |--------------------------------------------------------------------------
    */

    public function building()
    {
        return $this->belongsTo(
            Building::class,
            'building_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | COLLEGE
    |--------------------------------------------------------------------------
    */

    public function college()
    {
        return $this->belongsTo(
            College::class,
            'college_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DEPARTMENT CHAIR
    |--------------------------------------------------------------------------
    */

    public function departmentChair()
    {
        return $this->belongsTo(
            User::class,
            'department_chair_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESERVATIONS
    |--------------------------------------------------------------------------
    */

    public function reservations()
    {
        return $this->hasMany(
            Reservation::class,
            'schedule_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS MERGES
    |--------------------------------------------------------------------------
    */

    public function classMerges()
    {
        return $this->hasMany(
            ClassMerge::class,
            'schedule_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MERGES
    |--------------------------------------------------------------------------
    */

    public function merges()
    {
        return $this->hasMany(
            ScheduleMerge::class,
            'schedule_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE PERIOD
    |--------------------------------------------------------------------------
    */

    public function hasSchedulePeriod(): bool
    {
        return
            !is_null(
                $this->schedule_start_date
            )
            &&
            !is_null(
                $this->schedule_expiration_date
            );
    }

    public function isActivePeriod(): bool
    {
        if (
            !$this->schedule_start_date ||
            !$this->schedule_expiration_date
        ) {
            return true;
        }

        $today = now()->startOfDay();

        return
            $today->gte(
                $this->schedule_start_date->copy()->startOfDay()
            )
            &&
            $today->lte(
                $this->schedule_expiration_date->copy()->endOfDay()
            );
    }
}