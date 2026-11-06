<?php

namespace App\Notifications;

use App\Models\RoomSwapRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RoomSwapNotification extends Notification
{
    use Queueable;

    public function __construct(
        public RoomSwapRequest $swap,
        public string $event
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $this->swap->loadMissing([
            'requester',
            'targetUser',
            'requesterRoom',
            'targetRoom',
        ]);

        $date = $this->swap->swap_date?->format('F j, Y') ?? 'the scheduled date';
        $time = trim((string) $this->swap->start_time) . '–' . trim((string) $this->swap->end_time);
        $requester = $this->swap->requester?->name ?? 'A faculty member';
        $target = $this->swap->targetUser?->name ?? 'the other faculty member';

        if ($this->event === 'requested') {
            $title = 'Room swap request';
            $message = $requester . ' requests a room swap with you for ' . $date . ' at ' . $time . '.';
        } elseif ($this->event === 'approved') {
            $title = 'Room swap accepted';
            $message = $target . ' accepted your room swap request for ' . $date . ' at ' . $time . '.';
        } else {
            $title = 'Room swap declined';
            $message = $target . ' declined your room swap request for ' . $date . ' at ' . $time . '.';
        }

        return [
            'type' => 'room_swap',
            'event' => $this->event,
            'room_swap_id' => (int) $this->swap->id,
            'title' => $title,
            'message' => $message,
            'swap_date' => $this->swap->swap_date?->format('Y-m-d'),
            'start_time' => $this->swap->start_time,
            'end_time' => $this->swap->end_time,
            'requester_room' => $this->swap->requesterRoom?->room_name,
            'target_room' => $this->swap->targetRoom?->room_name,
        ];
    }
}
