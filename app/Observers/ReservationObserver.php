<?php

namespace App\Observers;

use App\Models\Reservation;
use App\Services\AuditLogRecorder;

class ReservationObserver
{
    public function created(Reservation $reservation): void
    {
        $this->record($reservation, 'created', null, $reservation->status, null, true);
    }

    public function updated(Reservation $reservation): void
    {
        if (!$reservation->wasChanged('status')) return;

        $this->record(
            $reservation,
            strtolower((string) $reservation->status),
            (string) $reservation->getRawOriginal('status'),
            (string) $reservation->status,
            $reservation->getOriginal(),
            true
        );
    }

    public function deleted(Reservation $reservation): void
    {
        $this->record($reservation, 'deleted', (string) $reservation->status, null, $reservation->getAttributes(), false);
    }

    private function record(Reservation $reservation, string $action, ?string $oldStatus, ?string $newStatus, ?array $oldData, bool $includeAfter): void
    {
        $reservation->loadMissing(['room.buildingRelation', 'room.department', 'schedule']);
        $roomId = (int) $reservation->room_id;
        $scheduleIds = array_values(array_filter([(int) $reservation->schedule_id]));
        $date = $reservation->date?->format('Y-m-d') ?: (string) $reservation->getRawOriginal('date');
        $focus = [
            'date' => $date,
            'day' => $reservation->day ?: ($date ? date('D', strtotime($date)) : null),
            'start_time' => $reservation->start_time,
            'end_time' => $reservation->end_time,
            'schedule_ids' => $scheduleIds,
            'academic_term_id' => $reservation->academic_term_id ?: $reservation->schedule?->academic_term_id,
        ];
        $recorder = app(AuditLogRecorder::class);
        $beforeState = $oldData ?: null;
        $afterState = $includeAfter ? $reservation->getAttributes() : null;
        $beforeSnapshot = $beforeState
            ? $recorder->captureRooms([$roomId], $scheduleIds, $focus, [], [$this->overlay($reservation, $oldStatus, $beforeState)])
            : $recorder->captureRooms([$roomId], $scheduleIds, $focus);
        $afterSnapshot = $includeAfter
            ? $recorder->captureRooms([$roomId], $scheduleIds, $focus, [], [$this->overlay($reservation, $newStatus, $afterState ?? [])])
            : $recorder->captureRooms([$roomId], $scheduleIds, $focus);

        $room = $reservation->room;
        $recorder->record('reservation', $action, [
            'actor' => strtolower((string) $newStatus) === 'expired' ? null : auth()->user(),
            'room_id' => $roomId,
            'schedule_id' => $reservation->schedule_id,
            // The reservation is deleted before this observer records the
            // history event. Keep its immutable details in the snapshots,
            // while leaving the nullable FK empty so the audit row survives.
            'reservation_id' => $action === 'deleted' ? null : $reservation->id,
            'room_ids' => [$roomId],
            'schedule_ids' => $scheduleIds,
            'department_ids' => array_filter([(int) $room?->department_id, (int) $reservation->schedule?->department_id]),
            'related_user_ids' => array_filter([(int) $reservation->user_id]),
            'reason' => $reservation->purpose,
            'description' => match ($action) {
                'created' => 'Reservation created for ' . ($reservation->purpose ?: 'room use') . '.',
                'deleted' => 'Reservation history removed after status ' . ($oldStatus ?: 'unknown') . '.',
                default => 'Reservation status changed from ' . ($oldStatus ?: 'none') . ' to ' . ($newStatus ?: 'unknown') . '.',
            },
            'before' => $beforeState ? array_merge($beforeState, ['status' => $oldStatus]) : null,
            'after' => $afterState ? array_merge($afterState, ['status' => $newStatus]) : null,
            'status_before' => $oldStatus,
            'status_after' => $newStatus,
            'snapshot_before' => $beforeSnapshot,
            'snapshot_after' => $afterSnapshot,
        ]);
    }

    private function overlay(Reservation $reservation, ?string $status, array $state): array
    {
        return [
            'room_id' => (int) $reservation->room_id,
            'day' => $state['day'] ?? $reservation->day,
            'start_time' => $state['start_time'] ?? $reservation->start_time,
            'end_time' => $state['end_time'] ?? $reservation->end_time,
            'label' => $state['purpose'] ?? $reservation->purpose ?? 'Room reservation',
            'description' => 'Reservation · ' . ($status ?: 'removed'),
            'owner' => $reservation->user?->name,
            'status' => $status,
            'highlight' => true,
        ];
    }
}
