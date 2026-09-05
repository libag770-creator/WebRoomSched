<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoomSwapRequest;

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

        // Allow other departments to use empty schedule slots
        'allow_other_departments',
    ];

   protected $casts = [
    'has_tv' => 'boolean',
    'has_projector' => 'boolean',
    'computers' => 'boolean',
    'capacity' => 'integer',
    'allow_other_departments' => 'boolean',
];

    public function drafts()
    {
        return $this->hasMany(
            ScheduleDraft::class,
            'room_id'
        );
    }

    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'department_id'
        );
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
        return $this->belongsTo(
            Building::class,
            'building_id'
        );
    }

    public function roomSwapRequests()
    {
        return $this->hasMany(
            RoomSwapRequest::class,
            'requester_room_id'
        );
    }

    public function receivedRoomSwapRequests()
    {
        return $this->hasMany(
            RoomSwapRequest::class,
            'target_room_id'
        );
    }
}