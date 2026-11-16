<?php

namespace App\Observers;

use App\Models\ScheduleOverride;
use App\Services\AuditLogRecorder;

class ScheduleOverrideObserver
{
    public function created(ScheduleOverride $override): void
    {
        $this->record($override, 'created', null, $override->status, null);
    }

    public function updated(ScheduleOverride $override): void
    {
        if (!$override->wasChanged('status')) return;

        $this->record(
            $override,
            strtolower((string) $override->status),
            $override->getRawOriginal('status'),
            $override->status,
            $override->getOriginal()
        );
    }

    private function record(ScheduleOverride $override, string $action, ?string $oldStatus, ?string $newStatus, ?array $oldData): void
    {
        $override->loadMissing(['room.buildingRelation', 'room.department', 'schedule.room']);
        $roomId = (int) ($override->room_id ?: $override->schedule?->room_id);
        if (!$roomId) return;

        $focus = [
            'date' => $override->override_date?->format('Y-m-d'),
            'day' => $override->override_date?->format('D'),
            'start_time' => $override->start_time,
            'end_time' => $override->end_time,
            'schedule_ids' => $override->schedule_id ? [(int) $override->schedule_id] : [],
            'academic_term_id' => $override->schedule?->academic_term_id,
        ];
        $overlaysBefore = $oldStatus === 'active' ? [$this->overlay($override, $oldStatus)] : [];
        $overlaysAfter = $newStatus === 'active' ? [$this->overlay($override, $newStatus)] : [];
        $recorder = app(AuditLogRecorder::class);
        $snapshotBefore = $recorder->captureRooms([$roomId], array_filter([(int) $override->schedule_id]), $focus, [], $overlaysBefore);
        $snapshotAfter = $recorder->captureRooms([$roomId], array_filter([(int) $override->schedule_id]), $focus, [], $overlaysAfter);
        $room = $override->room ?: $override->schedule?->room;
        $affectedSchedules = collect($snapshotAfter['rooms'] ?? [])
            ->flatMap(fn ($sheet) => $sheet['entries'] ?? [])
            ->filter(fn ($entry) => ($entry['kind'] ?? null) === 'schedule'
                && strtoupper(substr((string) ($entry['day'] ?? ''), 0, 3)) === strtoupper(substr((string) ($focus['day'] ?? ''), 0, 3))
                && !empty($entry['start_time']) && !empty($entry['end_time'])
                && $entry['start_time'] < ($focus['end_time'] ?? '00:00')
                && $entry['end_time'] > ($focus['start_time'] ?? '23:59'));
        $affectedScheduleIds = $affectedSchedules->pluck('id')->map(fn ($id) => (int) $id)->merge(array_filter([(int) $override->schedule_id]))->unique()->values()->all();

        $recorder->record('override', $action, [
            'actor' => strtolower((string) $newStatus) === 'expired' ? null : auth()->user(),
            'room_id' => $roomId,
            'schedule_id' => $affectedScheduleIds[0] ?? null,
            'room_ids' => [$roomId],
            'schedule_ids' => $affectedScheduleIds,
            'department_ids' => $affectedSchedules->pluck('department_id')->map(fn ($id) => (int) $id)->merge(array_filter([(int) $room?->department_id, (int) $override->schedule?->department_id]))->unique()->values()->all(),
            'related_user_ids' => array_filter([(int) $override->created_by, (int) $override->faculty_id]),
            'reason' => $override->reason,
            'description' => $action === 'created'
                ? 'Room override reserved ' . ($room?->room_name ?? 'a room') . ' for ' . ($override->occupied_by ?: 'an event') . '.'
                : 'Room override status changed from ' . ($oldStatus ?: 'none') . ' to ' . ($newStatus ?: 'unknown') . '.',
            'before' => $oldData ? array_merge($oldData, ['status' => $oldStatus]) : null,
            'after' => $override->getAttributes(),
            'status_before' => $oldStatus,
            'status_after' => $newStatus,
            'snapshot_before' => $snapshotBefore,
            'snapshot_after' => $snapshotAfter,
        ]);
    }

    private function overlay(ScheduleOverride $override, string $status): array
    {
        return [
            'room_id' => (int) ($override->room_id ?: $override->schedule?->room_id),
            'day' => $override->override_date?->format('D'),
            'start_time' => $override->start_time,
            'end_time' => $override->end_time,
            'label' => $override->occupied_by ?: 'Room override',
            'description' => $override->reason,
            'owner' => $override->createdBy?->name,
            'status' => ucfirst($status),
            'highlight' => true,
        ];
    }
}
