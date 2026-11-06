<?php

namespace App\Notifications;

use App\Models\ScheduleOverride;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ScheduleOverrideNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ScheduleOverride $override,
        public string $event = 'created'
    )
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $this->override->loadMissing(['schedule.room']);
        $schedule = $this->override->schedule;
        $date = $this->override->override_date?->format('F j, Y') ?? 'the selected date';
        $subject = $schedule?->subject ?: ($schedule?->course_code ?: 'your class');
        $room = $schedule?->room?->room_name ?: 'the scheduled room';
        $time = trim((string) ($schedule?->time ?? ''));

        $isCancelled = $this->event === 'cancelled';

        return [
            'type' => 'schedule_override',
            'event' => $this->event,
            'schedule_override_id' => (int) $this->override->id,
            'title' => $isCancelled ? 'Schedule override cancelled' : 'Your schedule is temporarily occupied',
            'message' => $isCancelled
                ? 'The temporary occupancy of ' . $subject . ' in ' . $room
                    . ' by ' . $this->override->occupied_by . ' on ' . $date
                    . ' was cancelled. Your regular schedule remains as usual.'
                : $subject . ' in ' . $room
                    . ($time !== '' ? ' (' . $time . ')' : '')
                    . ' will be occupied by ' . $this->override->occupied_by
                    . ' on ' . $date . ' for ' . $this->override->reason
                    . '. Your regular schedule returns after that day.',
            'override_date' => $this->override->override_date?->format('Y-m-d'),
            'occupied_by' => $this->override->occupied_by,
            'reason' => $this->override->reason,
            'schedule_id' => (int) $this->override->schedule_id,
            'url' => route('faculty.myschedules'),
        ];
    }
}
