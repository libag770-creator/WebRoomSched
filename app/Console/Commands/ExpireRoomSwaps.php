<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\RoomSwapRequest;
use App\Models\Schedule;
use App\Services\AuditLogRecorder;

class ExpireRoomSwaps extends Command
{
    protected $signature = 'room-swaps:expire';

    protected $description =
        'Restore original rooms after temporary room swaps expire';

    public function handle()
    {
        $now = now();

        $swaps = RoomSwapRequest::where(
            'status',
            'approved'
        )
        ->where('swap_type', 'temporary')
        ->whereNotNull('swap_date')
        ->whereNotNull('end_time')
        ->get();

        foreach ($swaps as $swap) {

            /*
            |--------------------------------------------------------------------------
            | GET DATE
            |--------------------------------------------------------------------------
            */

            $date = $swap->swap_date;

            if ($date instanceof \Carbon\Carbon) {
                $dateString = $date->format('Y-m-d');
            } else {
                $dateString = \Carbon\Carbon::parse(
                    $date
                )->format('Y-m-d');
            }


            /*
            |--------------------------------------------------------------------------
            | GET END TIME
            |--------------------------------------------------------------------------
            |
            | Because the model casts end_time as datetime:H:i,
            | Laravel may return a Carbon object here.
            |
            */

            $endTime = $swap->end_time;

            if ($endTime instanceof \Carbon\Carbon) {

                $timeString =
                    $endTime->format('H:i:s');

            } else {

                $timeString =
                    \Carbon\Carbon::parse(
                        $endTime
                    )->format('H:i:s');
            }


            /*
            |--------------------------------------------------------------------------
            | BUILD EXPIRATION DATETIME
            |--------------------------------------------------------------------------
            */

            $expiration =
                \Carbon\Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $dateString . ' ' . $timeString
                );


            /*
            |--------------------------------------------------------------------------
            | NOT EXPIRED YET
            |--------------------------------------------------------------------------
            */

            if ($now->lt($expiration)) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | RESTORE ORIGINAL ROOMS
            |--------------------------------------------------------------------------
            */

            try {

                DB::transaction(
                    function () use ($swap) {

                        $swap->loadMissing(['requesterRoom.department', 'targetRoom.department', 'requesterSchedule', 'targetSchedule']);
                        $roomIds = array_values(array_unique(array_filter([(int) $swap->requester_room_id, (int) $swap->target_room_id])));
                        $scheduleIds = array_values(array_filter([(int) $swap->requester_schedule_id, (int) $swap->target_schedule_id]));
                        $focusSchedule = $swap->requesterSchedule ?: $swap->targetSchedule;
                        $focus = [
                            'date' => $swap->swap_date?->format('Y-m-d'),
                            'day' => $focusSchedule?->day,
                            'start_time' => $swap->start_time ?: $focusSchedule?->start_time,
                            'end_time' => $swap->end_time ?: $focusSchedule?->end_time,
                            'schedule_ids' => $scheduleIds,
                            'academic_term_id' => $focusSchedule?->academic_term_id,
                        ];
                        $recorder = app(AuditLogRecorder::class);
                        $beforeSnapshot = $recorder->captureRooms($roomIds, $scheduleIds, $focus, [
                            (int) $swap->requester_schedule_id => (int) $swap->target_room_id,
                            (int) $swap->target_schedule_id => (int) $swap->requester_room_id,
                        ]);
                        $beforeData = ['status' => $swap->status, 'swap_type' => $swap->swap_type];

                        /*
                        |--------------------------------------------------------------------------
                        | LOCK REQUESTER SCHEDULE
                        |--------------------------------------------------------------------------
                        */

                        $requesterSchedule =
                            Schedule::where(
                                'id',
                                $swap->requester_schedule_id
                            )
                            ->lockForUpdate()
                            ->first();


                        /*
                        |--------------------------------------------------------------------------
                        | LOCK TARGET SCHEDULE
                        |--------------------------------------------------------------------------
                        */

                        $targetSchedule =
                            Schedule::where(
                                'id',
                                $swap->target_schedule_id
                            )
                            ->lockForUpdate()
                            ->first();


                        /*
                        |--------------------------------------------------------------------------
                        | SCHEDULES NO LONGER EXIST
                        |--------------------------------------------------------------------------
                        */

                        if (
                            !$requesterSchedule ||
                            !$targetSchedule
                        ) {

                            $swap->update([
                                'status' =>
                                    'expired'
                            ]);

                            $recorder->record('room_swap', 'expired', [
                                'actor' => null,
                                'room_ids' => $roomIds,
                                'schedule_ids' => $scheduleIds,
                                'related_user_ids' => [(int) $swap->requester_id, (int) $swap->target_user_id],
                                'department_ids' => [(int) $swap->requesterRoom?->department_id, (int) $swap->targetRoom?->department_id],
                                'reason' => $swap->reason,
                                'description' => 'Temporary room swap ended after one or both schedules were removed.',
                                'before' => $beforeData,
                                'after' => ['status' => 'expired', 'swap_type' => $swap->swap_type],
                                'status_before' => 'approved',
                                'status_after' => 'expired',
                                'snapshot_before' => $beforeSnapshot,
                                'snapshot_after' => $recorder->captureRooms($roomIds, $scheduleIds, $focus),
                            ]);

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | RESTORE REQUESTER'S ORIGINAL ROOM
                        |--------------------------------------------------------------------------
                        */

                        $requesterSchedule->update([
                            'room_id' =>
                                $swap->requester_room_id
                        ]);


                        /*
                        |--------------------------------------------------------------------------
                        | RESTORE TARGET FACULTY'S ORIGINAL ROOM
                        |--------------------------------------------------------------------------
                        */

                        $targetSchedule->update([
                            'room_id' =>
                                $swap->target_room_id
                        ]);


                        /*
                        |--------------------------------------------------------------------------
                        | MARK SWAP AS EXPIRED
                        |--------------------------------------------------------------------------
                        */

                        $swap->update([
                            'status' =>
                                'expired'
                        ]);

                        $recorder->record('room_swap', 'expired', [
                            'actor' => null,
                            'room_ids' => $roomIds,
                            'schedule_ids' => $scheduleIds,
                            'related_user_ids' => [(int) $swap->requester_id, (int) $swap->target_user_id],
                            'department_ids' => [(int) $swap->requesterRoom?->department_id, (int) $swap->targetRoom?->department_id, (int) $requesterSchedule->department_id, (int) $targetSchedule->department_id],
                            'reason' => $swap->reason,
                            'description' => 'Temporary room swap ended; both saved schedules returned to their original rooms.',
                            'before' => $beforeData,
                            'after' => ['status' => 'expired', 'swap_type' => $swap->swap_type],
                            'status_before' => 'approved',
                            'status_after' => 'expired',
                            'snapshot_before' => $beforeSnapshot,
                            'snapshot_after' => $recorder->captureRooms($roomIds, $scheduleIds, $focus),
                        ]);
                    }
                );


                $this->info(
                    'Room swap #' .
                    $swap->id .
                    ' expired. Original rooms restored.'
                );

            } catch (\Throwable $e) {

                $this->error(
                    'Could not expire room swap #' .
                    $swap->id .
                    ': ' .
                    $e->getMessage()
                );
            }
        }

        return Command::SUCCESS;
    }
}
