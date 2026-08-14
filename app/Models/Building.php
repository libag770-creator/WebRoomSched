<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'department_id',
        'name'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'building_id');
    }
}