<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomReassignment extends Model
{
    use HasFactory;

    protected $table = 'room_reassignments';

    
protected $fillable = [
    'schedule_id',
    'original_room_id',
    'new_room_id',
    'day',
    'start_time',
    'end_time',
    'reason',
    'details',
    'starts_at',
    'expires_at',
    'status',

   
    'occupancy_decision',
    'occupied_by',
    'occupancy_purpose',
    'occupancy_details',
    'not_occupied_reason',
    'not_occupied_details',
];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | ORIGINAL SCHEDULE
    |--------------------------------------------------------------------------
    */

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ORIGINAL ROOM
    |--------------------------------------------------------------------------
    */

    public function originalRoom()
    {
        return $this->belongsTo(Room::class, 'original_room_id');
    }

    /*
    |--------------------------------------------------------------------------
    | NEW / TEMPORARY ROOM
    |--------------------------------------------------------------------------
    */

    public function newRoom()
    {
        return $this->belongsTo(Room::class, 'new_room_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE REASSIGNMENT
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        $now = now();

        return $query
            ->where('status', 'active')
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', $now);
            });
    }

    /*
    |--------------------------------------------------------------------------
    | EXPIRED REASSIGNMENT
    |--------------------------------------------------------------------------
    */

    public function scopeExpired($query)
    {
        $now = now();

        return $query->where(function ($q) use ($now) {
            $q->where('status', 'expired')
                ->orWhere(function ($q2) use ($now) {
                    $q2->where('status', 'active')
                        ->whereNotNull('expires_at')
                        ->where('expires_at', '<=', $now);
                });
        });
    }
}