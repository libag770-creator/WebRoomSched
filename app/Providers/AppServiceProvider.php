<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Reservation;
use App\Models\ScheduleOverride;
use App\Models\RoomReassignment;
use App\Observers\ReservationObserver;
use App\Observers\RoomReassignmentObserver;
use App\Observers\ScheduleOverrideObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
{
    Reservation::observe(ReservationObserver::class);
    ScheduleOverride::observe(ScheduleOverrideObserver::class);
    RoomReassignment::observe(RoomReassignmentObserver::class);

    if (app()->environment('production')) {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
}
