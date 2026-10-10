<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleDraft extends Model
{
    use HasFactory;

    protected $table = 'schedule_drafts';

    protected $fillable = [

        'academic_term_id',

        'room_id',

        'created_by',

        'faculty_id',

        'department_id',

        'semester',

        'academic_year',

        'day',

        'time',

        'row_position',

        'course_code',

        'subject',

        'year_level',

        'major',

        'program',

        'subject_type',

        'instructor',

        'color',
    ];

    protected $casts = [
        'academic_term_id' => 'integer',
    ];

    public function academicTerm()
    {
        return $this->belongsTo(
            AcademicTerm::class,
            'academic_term_id'
        );
    }

    public function room()
    {
        return $this->belongsTo(
            Room::class,
            'room_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function faculty()
    {
        return $this->belongsTo(
            User::class,
            'faculty_id'
        );
    }

    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'department_id'
        );
    }
}