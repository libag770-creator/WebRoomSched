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
            'requesterSchedule',
            'targetSchedule',
        ]);

        $isPermanent = ($this->swap->swap_type ?? 'temporary') === 'permanent';
        $date = $this->swap->swap_date?->format('F j, Y') ?? 'the scheduled date';
        $time = trim((string) $this->swap->start_time) . '–' . trim((string) $this->swap->end_time);
        $requester = $this->swap->requester?->name ?? 'A instructor';
        $target = $this->swap->targetUser?->name ?? 'the other instructor';
        $requesterCourse = trim(($this->swap->requesterSchedule?->course_code ?? 'Class') . ' — ' . ($this->swap->requesterSchedule?->subject ?? ''));
        $targetCourse = trim(($this->swap->targetSchedule?->course_code ?? 'Class') . ' — ' . ($this->swap->targetSchedule?->subject ?? ''));

        if ($this->event === 'requested') {
            $title = 'Room swap request';
            $message = $isPermanent
                ? $requester . ' requests a permanent room exchange for ' . $requesterCourse . ' and ' . $targetCourse . '. Accepting changes both recurring schedules.'
                : $requester . ' requests a temporary room swap with you for ' . $date . ' at ' . $time . '.';
        } elseif ($this->event === 'approved') {
            $title = 'Room swap accepted';
            $message = $isPermanent
                ? $target . ' accepted your permanent room swap. ' . $requesterCourse . ' now uses ' . ($this->swap->targetRoom?->room_name ?? 'the other room') . ', and ' . $targetCourse . ' now uses ' . ($this->swap->requesterRoom?->room_name ?? 'your room') . '.'
                : $target . ' accepted your temporary room swap request for ' . $date . ' at ' . $time . '.';
        } elseif ($this->event === 'permanent_chair') {
            $title = 'Permanent room assignment changed';
            $message = $requesterCourse . ' moved to ' . ($this->swap->targetRoom?->room_name ?? 'the other room')
                . ' and ' . $targetCourse . ' moved to ' . ($this->swap->requesterRoom?->room_name ?? 'the other room')
                . ' after a permanent instructor room swap.';
        } else {
            $title = 'Room swap declined';
            $message = $isPermanent
                ? $target . ' declined your permanent room swap request. Neither schedule was changed.'
                : $target . ' declined your room swap request for ' . $date . ' at ' . $time . '.';
            if ($this->swap->decline_reason) {
                $message .= ' Reason: ' . $this->swap->decline_reason;
            }
        }

        return [
            'type' => $this->event === 'permanent_chair' ? 'room_swap_chair' : 'room_swap',
            'event' => $this->event,
            'swap_type' => $isPermanent ? 'permanent' : 'temporary',
            'room_swap_id' => (int) $this->swap->id,
            'title' => $title,
            'message' => $message,
            'decline_reason' => $this->event === 'declined' ? $this->swap->decline_reason : null,
            'url' => $this->event === 'permanent_chair' ? route('chair.modifyschedule') : null,
            'swap_date' => $this->swap->swap_date?->format('Y-m-d'),
            'start_time' => $this->swap->start_time,
            'end_time' => $this->swap->end_time,
            'requester_room' => $this->swap->requesterRoom?->room_name,
            'target_room' => $this->swap->targetRoom?->room_name,
        ];
    }
}
