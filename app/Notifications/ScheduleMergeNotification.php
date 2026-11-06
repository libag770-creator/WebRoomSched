<?php

namespace App\Notifications;

use App\Models\ScheduleMerge;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ScheduleMergeNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ScheduleMerge $merge,
        public string $event
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $this->merge->loadMissing([
            'schedule.room',
            'schedule.department',
            'department',
            'requester',
            'subject.program.department',
            'facultySubject.subjectCatalog.program.department',
        ]);

        $subject = $this->merge->subject
            ?? $this->merge->facultySubject?->subjectCatalog;
        $courseCode = $subject?->course_code
            ?? $this->merge->facultySubject?->course_code
            ?? $this->merge->schedule?->course_code
            ?? 'Course';
        $courseTitle = $subject?->course_title
            ?? $this->merge->facultySubject?->subject
            ?? $this->merge->schedule?->subject
            ?? '';
        $program = $subject?->program?->name
            ?? $this->merge->facultySubject?->program
            ?? 'Program';
        $hostCode = $this->merge->schedule?->course_code ?? 'Scheduled class';
        $hostTitle = $this->merge->schedule?->subject;
        $host = trim($hostCode . ($hostTitle ? ' — ' . $hostTitle : ''));
        $room = $this->merge->schedule?->room?->room_name;
        $roomText = $room ? ' in ' . $room : '';
        $requester = $this->merge->department?->name ?? 'Another college';
        $decisionNote = trim((string) $this->merge->decision_note);

        [$title, $message, $anchor] = match ($this->event) {
            'requested' => [
                'Course merge request',
                $requester . ' requests to add ' . $courseCode . ' — ' . $courseTitle
                    . ' (' . $program . ') to ' . $host . $roomText . '.',
                '#merge-requests',
            ],
            'approved' => [
                'Course merge approved',
                'Your request to add ' . $courseCode . ' — ' . $courseTitle
                    . ' (' . $program . ') to ' . $host . ' was approved.',
                '#my-merge-requests',
            ],
            'rejected' => [
                'Course merge declined',
                'Your request to add ' . $courseCode . ' — ' . $courseTitle
                    . ' (' . $program . ') to ' . $host . ' was declined.'
                    . ($decisionNote !== '' ? ' Reason: ' . $decisionNote : ''),
                '#my-merge-requests',
            ],
            'cancelled' => [
                'Course merge request cancelled',
                $requester . ' cancelled the request to add ' . $courseCode
                    . ' — ' . $courseTitle . ' (' . $program . ') to ' . $host . '.',
                '#merge-requests',
            ],
            default => [
                'Course merge removed',
                'The shared course ' . $courseCode . ' — ' . $courseTitle
                    . ' (' . $program . ') was removed from ' . $host . '.',
                '#my-merge-requests',
            ],
        };

        return [
            'type' => 'schedule_merge',
            'event' => $this->event,
            'merge_id' => (int) $this->merge->id,
            'request_group_id' => $this->merge->request_group_id,
            'title' => $title,
            'message' => $message,
            'url' => route('chair.dashboard') . $anchor,
        ];
    }
}
