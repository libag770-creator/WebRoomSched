<?php

namespace App\Observers;

use App\Models\RoomReassignment;
use App\Services\AuditLogRecorder;

class RoomReassignmentObserver
{
    public function created(RoomReassignment $reassignment): void
    {
        $this->record($reassignment, 'reassigned', null, $reassignment->status, null);
    }

    public function updated(RoomReassignment $reassignment): void
    {
        if (!$reassignment->wasChanged('status') && !$reassignment->wasChanged(['new_room_id', 'start_time', 'end_time', 'day'])) return;
        $old = $reassignment->getOriginal();
        $this->record($reassignment, $reassignment->wasChanged('status') ? strtolower((string) $reassignment->status) : 'updated', $old['status'] ?? null, $reassignment->status, $old);
    }

    private function record(RoomReassignment $reassignment, string $action, ?string $oldStatus, ?string $newStatus, ?array $oldData): void
    {
        $reassignment->loadMissing(['schedule', 'originalRoom.department', 'newRoom.department']);
        $schedule = $reassignment->schedule;
        $roomIds = array_values(array_unique(array_filter([
            (int) $reassignment->original_room_id,
            (int) ($oldData['new_room_id'] ?? 0),
            (int) $reassignment->new_room_id,
        ])));
        $scheduleIds = array_values(array_filter([(int) $reassignment->schedule_id]));
        $focus = [
            'day' => $reassignment->day,
            'start_time' => $reassignment->start_time,
            'end_time' => $reassignment->end_time,
            'schedule_ids' => $scheduleIds,
            'academic_term_id' => $schedule?->academic_term_id,
        ];
        $recorder = app(AuditLogRecorder::class);
        $beforeActive = $oldData ? ($oldStatus === 'active') : false;
        $afterActive = $newStatus === 'active';
        $beforeAssignments = $beforeActive ? [$reassignment->schedule_id => (int) ($oldData['new_room_id'] ?? $reassignment->new_room_id)] : [];
        $afterAssignments = $afterActive ? [$reassignment->schedule_id => (int) $reassignment->new_room_id] : [];
        $snapshotBefore = $recorder->captureRooms($roomIds, $scheduleIds, $focus, $beforeAssignments);
        $snapshotAfter = $recorder->captureRooms($roomIds, $scheduleIds, $focus, $afterAssignments);

        $recorder->record('room_swap', $action, [
            'actor' => strtolower((string) $newStatus) === 'expired' ? null : auth()->user(),
            'room_ids' => $roomIds,
            'schedule_ids' => $scheduleIds,
            'department_ids' => [(int) $reassignment->originalRoom?->department_id, (int) $reassignment->newRoom?->department_id, (int) $schedule?->department_id],
            'reason' => $reassignment->reason,
            'description' => 'Schedule room reassignment from ' . ($reassignment->originalRoom?->room_name ?? 'the original room')
                . ' to ' . ($reassignment->newRoom?->room_name ?? 'a replacement room') . '.',
            'before' => $oldData ? array_merge($oldData, ['status' => $oldStatus]) : null,
            'after' => $reassignment->getAttributes(),
            'status_before' => $oldStatus,
            'status_after' => $newStatus,
            'snapshot_before' => $snapshotBefore,
            'snapshot_after' => $snapshotAfter,
        ]);
    }
}
