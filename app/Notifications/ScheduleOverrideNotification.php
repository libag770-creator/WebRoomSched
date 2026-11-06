<?php

namespace App\Notifications;

use App\Models\ScheduleOverride;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ScheduleOverrideNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ScheduleOverride $override,
        public string $event = 'created'
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $this->override->loadMissing(['room', 'schedule.room']);

        $room = $this->override->room?->room_name
            ?: $this->override->schedule?->room?->room_name
            ?: 'the scheduled room';
        $date = $this->override->override_date?->format('F j, Y') ?? 'the selected date';
        $start = $this->override->start_time ? Carbon::parse($this->override->start_time)->format('g:i A') : null;
        $end = $this->override->end_time ? Carbon::parse($this->override->end_time)->format('g:i A') : null;
        $time = $start && $end ? $start . '–' . $end : trim((string) ($this->override->schedule?->time ?? ''));
        $eventLabel = $this->event === 'cancelled' ? 'cancelled' : 'reserved';

        return [
            'type' => 'schedule_override',
            'event' => $this->event,
            'schedule_override_id' => (int) $this->override->id,
            'title' => $this->event === 'cancelled' ? 'Room reservation cancelled' : 'Room temporarily reserved',
            'message' => $room . ($time !== '' ? ' (' . $time . ')' : '')
                . ' is ' . $eventLabel . ' on ' . $date
                . ' by ' . ($this->override->occupied_by ?: 'the administrator')
                . ' for ' . ($this->override->reason ?: 'a scheduled event')
                . ($this->event === 'cancelled' ? '.' : '. Your regular timetable resumes outside this time.'),
            'override_date' => $this->override->override_date?->format('Y-m-d'),
            'room_id' => (int) ($this->override->room_id ?? $this->override->schedule?->room_id),
            'start_time' => $start,
            'end_time' => $end,
            'occupied_by' => $this->override->occupied_by,
            'reason' => $this->override->reason,
            'schedule_id' => $this->override->schedule_id ? (int) $this->override->schedule_id : null,
            'url' => route('faculty.myschedules'),
        ];
    }
}
