<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $table = 'curricula';

    protected $fillable = [
        'department_id',
        'program',
        'major',
        'curriculum_code',
        'status',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function courses()
    {
        return $this->hasMany(CurriculumCourse::class, 'curriculum_id');
    }

    public function documents()
    {
        return $this->hasMany(CurriculumDocument::class, 'curriculum_id');
    }

    public function gridLayout()
    {
        return $this->hasOne(CurriculumGridLayout::class, 'curriculum_id');
    }
}
