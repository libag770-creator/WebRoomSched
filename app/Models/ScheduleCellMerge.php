<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleCellMerge extends Model
{
    protected $fillable = [
        'room_id',
        'academic_term_id',
        'axis',
        'cells',
    ];

    protected $casts = [
        'room_id' => 'integer',
        'academic_term_id' => 'integer',
        'cells' => 'array',
    ];
}
