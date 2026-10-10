<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumGridLayout extends Model
{
    use HasFactory;

    protected $table = 'curriculum_grid_layouts';

    protected $fillable = [
        'curriculum_id',
        'layout_json',
    ];

    protected $casts = [
        'layout_json' => 'array',
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }
}
