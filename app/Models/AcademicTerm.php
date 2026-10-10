<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicTerm extends Model
{
    use HasFactory;

    protected $table = 'academic_terms';

    protected $fillable = [
        'academic_year',
        'semester',
        'status',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function schedules()
    {
        return $this->hasMany(
            Schedule::class,
            'academic_term_id'
        );
    }

    public function drafts()
    {
        return $this->hasMany(
            ScheduleDraft::class,
            'academic_term_id'
        );
    }
}