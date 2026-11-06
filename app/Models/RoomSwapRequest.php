<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'swap_type',
        'swap_date',
        'start_time',
        'end_time',
        'reason',
        'decline_reason',
        'status',
    ];

    protected $casts = [
        'swap_date' => 'date',
    ];

    public static function expireElapsedTemporarySwaps(): int
    {
        $today = Carbon::today()->toDateString();
        $currentTime = Carbon::now()->format('H:i:s');

        return static::query()
            ->whereRaw('LOWER(TRIM(status)) = ?', ['approved'])
            ->where(function ($query) {
                $query->whereNull('swap_type')->orWhere('swap_type', 'temporary');
            })
            ->whereNotNull('swap_date')
            ->whereNotNull('end_time')
            ->where(function ($query) use ($today, $currentTime) {
                $query->whereDate('swap_date', '<', $today)
                    ->orWhere(function ($sameDay) use ($today, $currentTime) {
                        $sameDay->whereDate('swap_date', '=', $today)
                            ->where('end_time', '<=', $currentTime);
                    });
            })
            ->update([
                'status' => 'expired',
                'updated_at' => Carbon::now(),
            ]);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function requesterSchedule()
    {
        return $this->belongsTo(Schedule::class, 'requester_schedule_id');
    }

    public function targetSchedule()
    {
        return $this->belongsTo(Schedule::class, 'target_schedule_id');
    }

    public function requesterRoom()
    {
        return $this->belongsTo(Room::class, 'requester_room_id');
    }

    public function targetRoom()
    {
        return $this->belongsTo(Room::class, 'target_room_id');
    }

    public function isActive(): bool
    {
        if ($this->status !== 'approved') {
            return false;
        }

        if (($this->swap_type ?? 'temporary') === 'permanent') {
            return true;
        }

        if (!$this->swap_date || !$this->start_time || !$this->end_time) return false;

        try {
            $date = $this->swap_date->format('Y-m-d');
            $start = Carbon::parse($date . ' ' . $this->start_time);
            $end = Carbon::parse($date . ' ' . $this->end_time);
            return now()->between($start, $end);
        } catch (\Throwable $exception) {
            return false;
        }
    }

    public function isExpired(): bool
    {
        if (($this->swap_type ?? 'temporary') === 'permanent' || !$this->swap_date || !$this->end_time) {
            return false;
        }

        try {
            $end = Carbon::parse($this->swap_date->format('Y-m-d') . ' ' . $this->end_time);
            return now()->greaterThanOrEqualTo($end);
        } catch (\Throwable $exception) {
            return false;
        }
    }
}
