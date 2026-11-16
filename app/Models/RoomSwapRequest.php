<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Services\AuditLogRecorder;

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

        $ids = static::query()
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
            ->pluck('id');

        $expiredCount = 0;
        foreach ($ids as $id) {
            $didExpire = DB::transaction(function () use ($id): bool {
                $swap = static::with(['requesterRoom.department', 'targetRoom.department', 'requesterSchedule', 'targetSchedule'])
                    ->lockForUpdate()->find($id);
                if (!$swap || strtolower(trim((string) $swap->status)) !== 'approved') return false;

                try {
                    $end = Carbon::parse($swap->swap_date->format('Y-m-d') . ' ' . $swap->end_time);
                } catch (\Throwable $exception) {
                    return false;
                }
                if (Carbon::now()->lt($end)) return false;

                $roomIds = array_values(array_unique(array_filter([(int) $swap->requester_room_id, (int) $swap->target_room_id])));
                $scheduleIds = array_values(array_filter([(int) $swap->requester_schedule_id, (int) $swap->target_schedule_id]));
                $schedule = $swap->requesterSchedule ?: $swap->targetSchedule;
                $focus = [
                    'date' => $swap->swap_date?->format('Y-m-d'),
                    'day' => $schedule?->day,
                    'start_time' => $swap->start_time ?: $schedule?->start_time,
                    'end_time' => $swap->end_time ?: $schedule?->end_time,
                    'schedule_ids' => $scheduleIds,
                    'academic_term_id' => $schedule?->academic_term_id,
                ];
                $recorder = app(AuditLogRecorder::class);
                $beforeAssignments = ($swap->swap_type ?? 'temporary') === 'temporary' ? [
                    (int) $swap->requester_schedule_id => (int) $swap->target_room_id,
                    (int) $swap->target_schedule_id => (int) $swap->requester_room_id,
                ] : [];
                $beforeSnapshot = $recorder->captureRooms($roomIds, $scheduleIds, $focus, $beforeAssignments);
                $before = ['status' => $swap->status, 'swap_type' => $swap->swap_type];
                $swap->status = 'expired';
                $swap->save();
                $afterSnapshot = $recorder->captureRooms($roomIds, $scheduleIds, $focus);
                $recorder->record('room_swap', 'expired', [
                    'actor' => null,
                    'room_ids' => $roomIds,
                    'schedule_ids' => $scheduleIds,
                    'related_user_ids' => [(int) $swap->requester_id, (int) $swap->target_user_id],
                    'department_ids' => [(int) $swap->requesterRoom?->department_id, (int) $swap->targetRoom?->department_id, (int) $swap->requesterSchedule?->department_id, (int) $swap->targetSchedule?->department_id],
                    'reason' => $swap->reason,
                    'description' => 'Temporary room swap ended; the schedules returned to their regular rooms.',
                    'before' => $before,
                    'after' => ['status' => $swap->status, 'swap_type' => $swap->swap_type],
                    'status_before' => 'approved',
                    'status_after' => 'expired',
                    'snapshot_before' => $beforeSnapshot,
                    'snapshot_after' => $afterSnapshot,
                ]);
                return true;
            });

            if ($didExpire) $expiredCount++;
        }

        return $expiredCount;
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
