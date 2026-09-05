<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultySubject extends Model
{
    protected $fillable = [
        'faculty_id',
        'course_code',
        'subject',
        'year_level',
    ];

    public function faculty()
    {
        return $this->belongsTo(
            User::class,
            'faculty_id'
        );
    }
}