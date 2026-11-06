<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultySubject extends Model
{
    protected $fillable = [
        'faculty_id',
        'subject_id',
        'subject_type',
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

    public function subjectCatalog()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function scheduleMerges()
    {
        return $this->hasMany(ScheduleMerge::class, 'faculty_subject_id');
    }

    public function sharedCohorts()
    {
        return $this->belongsToMany(
            Subject::class,
            'faculty_subject_cohorts',
            'faculty_subject_id',
            'subject_id'
        )->with('program');
    }
}
