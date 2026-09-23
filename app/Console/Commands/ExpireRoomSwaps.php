<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\RoomSwapRequest;
use App\Models\Schedule;

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