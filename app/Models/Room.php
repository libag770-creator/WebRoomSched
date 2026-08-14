<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
 protected $fillable = [
    'department_id',
    'building_id',
    'building',
    'room_name',
    'capacity',
    'has_tv',
    'has_projector',
    'computers',
    'purpose',
    'description',
];
    protected $casts = [
        'tv' => 'boolean',
        'projector' => 'boolean',
        'computers' => 'boolean',
        'capacity' => 'integer',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    
    public function buildingRelation()
{
    return $this->belongsTo(Building::class, 'building_id');
}
}