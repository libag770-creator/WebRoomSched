<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumCourse extends Model
{
    use HasFactory;

    protected $table = 'curriculum_courses';

    protected $fillable = [
        'curriculum_id',
        'course_code',
        'subject',
        'units',
        'lec_units',
        'lab_units',
        'lec_hours',
        'lab_hours',
        'grade',
        'year_level',
        'semester',
        'subject_type',
        'prerequisite',
    ];

    protected $casts = [
        'units' => 'float',
        'lec_units' => 'float',
        'lab_units' => 'float',
        'lec_hours' => 'float',
        'lab_hours' => 'float',
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }
}
