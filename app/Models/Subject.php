<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'program_id',
        'year_level',
        'semester',
        'course_code',
        'course_title',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public static function typeFromCourseCode(string $courseCode): string
    {
        return preg_match('/\.1\s*$/', trim($courseCode)) === 1
            ? 'Lab'
            : 'Lec';
    }
}
