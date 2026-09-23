<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomSwapRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_id',
        'target_user_id',
        'requester_schedule_id',
        'target_schedule_id',
        'requester_room_id',
        'target_room_id',
        'swap_date',
        'start_time',
        'end_time',
        'reason',
        'status',
    ];

    protected $casts = [
        'swap_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /*
    |--------------------------------------------------------------------------
    | FACULTY A — REQUESTER
    |--------------------------------------------------------------------------
    */

    public function requester()
    {
        return $this->belongsTo(
            User::class,
            'requester_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FACULTY B — TARGET FACULTY
    |--------------------------------------------------------------------------
    */

    public function targetUser()
    {
        return $this->belongsTo(
            User::class,
            'target_user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FACULTY A — SCHEDULE
    |--------------------------------------------------------------------------
    */

    public function requesterSchedule()
    {
        return $this->belongsTo(
            Schedule::class,
            'requester_schedule_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FACULTY B — SCHEDULE
    |--------------------------------------------------------------------------
    */

    public function targetSchedule()
    {
        return $this->belongsTo(
            Schedule::class,
            'target_schedule_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FACULTY A — ORIGINAL ROOM
    |--------------------------------------------------------------------------
    */

    public function requesterRoom()
    {
        return $this->belongsTo(
            Room::class,
            'requester_room_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FACULTY B — ORIGINAL ROOM
    |--------------------------------------------------------------------------
    */

    public function targetRoom()
    {
        return $this->belongsTo(
            Room::class,
            'target_room_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK IF APPROVED SWAP IS CURRENTLY ACTIVE
    |--------------------------------------------------------------------------
    */

    public function isActive()
    {
        if ($this->status !== 'approved') {
            return false;
        }

        if (!$this->swap_date) {
            return false;
        }

        if (!$this->start_time || !$this->end_time) {
            return false;
        }

        $now = now();

        $start = \Carbon\Carbon::parse(
            $this->swap_date->format('Y-m-d')
            . ' '
            . $this->start_time
        );

        $end = \Carbon\Carbon::parse(
            $this->swap_date->format('Y-m-d')
            . ' '
            . $this->end_time
        );

        return $now->between(
            $start,
            $end
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK IF APPROVED SWAP HAS EXPIRED
    |--------------------------------------------------------------------------
    */

    public function isExpired()
    {
        if (!$this->swap_date) {
            return false;
        }

        if (!$this->end_time) {
            return false;
        }

        $now = now();

        $end = \Carbon\Carbon::parse(
            $this->swap_date->format('Y-m-d')
            . ' '
            . $this->end_time
        );

        return $now->greaterThanOrEqualTo(
            $end
        );
    }
}