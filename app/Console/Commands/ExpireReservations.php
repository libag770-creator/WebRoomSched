<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class ExpireReservations extends Command
{
    protected $signature = 'reservations:expire';

    protected $description =
        'Automatically expire approved room reservations after their end time';

    public function handle()
    {
        $reservations = Reservation::where(
            'status',
            'Approved'
        )
        ->whereNotNull('date')
        ->whereNotNull('end_time')
        ->get();

        $expiredCount = 0;

        foreach ($reservations as $reservation) {

            /*
            |--------------------------------------------------------------------------
            | Get reservation date
            |--------------------------------------------------------------------------
            */

            $date = Carbon::parse(
                $reservation->date
            )->format('Y-m-d');

            /*
            |--------------------------------------------------------------------------
            | Safely get end time
            |--------------------------------------------------------------------------
            */

            if ($reservation->end_time instanceof Carbon) {

                $endTime = $reservation->end_time->format('H:i:s');

            } else {

                $endTime = Carbon::parse(
                    $reservation->end_time
                )->format('H:i:s');
            }

            /*
            |--------------------------------------------------------------------------
            | Build expiration datetime
            |--------------------------------------------------------------------------
            */

            $expiration = Carbon::parse(
                $date . ' ' . $endTime
            );

            /*
            |--------------------------------------------------------------------------
            | Expire reservation
            |--------------------------------------------------------------------------
            */

            if (now()->greaterThanOrEqualTo($expiration)) {

                $reservation->update([
                    'status' => 'Expired'
                ]);

                $expiredCount++;
            }
        }

        $this->info(
            $expiredCount .
            ' reservation(s) expired.'
        );

        return Command::SUCCESS;
    }
}