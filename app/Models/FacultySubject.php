<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultySubject extends Model
{
    protected $fillable = [
        'faculty_id',
        'curriculum_course_id',
        'course_code',
        'subject',
        'year_level',
        'major',
        'program',
    ];

    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    public function curriculumCourse()
    {
        return $this->belongsTo(CurriculumCourse::class, 'curriculum_course_id');
    }

    public function scheduleMerges()
    {
        return $this->hasMany(ScheduleMerge::class, 'faculty_subject_id');
    }
}