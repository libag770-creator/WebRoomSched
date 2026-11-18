<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\AuditLogAccess;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use App\Models\AcademicTerm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditLogRecorder
{
    /** Capture an immutable, room-oriented view of the timetable at this moment. */
    public function captureRooms(
        array $roomIds,
        array $scheduleIds = [],
        array $focus = [],
        array $assignments = [],
        array $overlays = []
    ): array {
        $roomIds = array_values(array_unique(array_filter(array_map('intval', $roomIds))));
        $scheduleIds = array_values(array_unique(array_filter(array_map('intval', $scheduleIds))));
        $rooms = Room::with(['buildingRelation', 'department'])->whereIn('id', $roomIds)->get()->keyBy('id');

        $termId = $focus['academic_term_id'] ?? null;
        if (!$termId && $scheduleIds) {
            $termId = Schedule::whereIn('id', $scheduleIds)->value('academic_term_id');
        }
        // Room-only overrides have no schedule_id. Infer their timetable term
        // from classes in the same room, day, and overlapping time range before
        // falling back to the globally active term (which can be unrelated).
        $matchingFocusSchedules = collect();
        if (!$termId && $roomIds && !empty($focus['day']) && !empty($focus['start_time']) && !empty($focus['end_time'])) {
            $focusDay = $this->dayLabel($focus['day']);
            $focusStart = $this->clock($focus['start_time']);
            $focusEnd = $this->clock($focus['end_time']);
            if ($focusStart && $focusEnd) {
                $matchingFocusSchedules = Schedule::query()->whereIn('room_id', $roomIds)->get()
                    ->filter(function (Schedule $schedule) use ($focusDay, $focusStart, $focusEnd) {
                        if ($this->dayLabel($schedule->day ?: $schedule->day_of_week) !== $focusDay) return false;
                        [$start, $end] = $this->scheduleRange($schedule->start_time, $schedule->end_time, $schedule->time);
                        return $start && $end && $start < $focusEnd && $end > $focusStart;
                    })->values();
                $activeTermId = AcademicTerm::where('is_active', true)->orderByDesc('id')->value('id');
                $termId = $matchingFocusSchedules->contains(fn (Schedule $schedule) => (int) $schedule->academic_term_id === (int) $activeTermId)
                    ? $activeTermId
                    : ($matchingFocusSchedules->count()
                        ? $matchingFocusSchedules->countBy('academic_term_id')->sortDesc()->keys()->first()
                        : null);
                if ($termId) {
                    $scheduleIds = array_values(array_unique(array_merge(
                        $scheduleIds,
                        $matchingFocusSchedules->where('academic_term_id', $termId)->pluck('id')->map(fn ($id) => (int) $id)->all()
                    )));
                    $focus['schedule_ids'] = $scheduleIds;
                    $focus['academic_term_id'] = (int) $termId;
                }
            }
        }
        if (!$termId) {
            $termId = AcademicTerm::where('is_active', true)->orderByDesc('id')->value('id');
        }
        if ($termId) {
            $focus['academic_term_id'] = (int) $termId;
            $term = AcademicTerm::find($termId);
            if ($term) {
                $focus['academic_term_label'] = trim(implode(' · ', array_filter([
                    $term->academic_year,
                    $term->semester,
                ])));
            }
        }

        $schedules = Schedule::query()
            ->where(function ($query) use ($roomIds, $scheduleIds) {
                if ($roomIds) $query->whereIn('room_id', $roomIds);
                if ($scheduleIds) $query->orWhereIn('id', $scheduleIds);
            })
            ->when($termId, fn ($query) => $query->where('academic_term_id', $termId))
            ->get();

        foreach ($schedules as $schedule) {
            $destinationId = (int) ($assignments[$schedule->id] ?? $schedule->room_id);
            if ($destinationId && !isset($rooms[$destinationId])) {
                $room = Room::with(['buildingRelation', 'department'])->find($destinationId);
                if ($room) $rooms->put($destinationId, $room);
            }
        }

        $entriesByRoom = [];
        foreach ($schedules as $schedule) {
            $roomId = (int) ($assignments[$schedule->id] ?? $schedule->room_id);
            if (!$roomId || !$rooms->has($roomId)) continue;
            [$startTime, $endTime] = $this->scheduleRange($schedule->start_time, $schedule->end_time, $schedule->time);
            $entriesByRoom[$roomId][] = [
                'id' => (int) $schedule->id,
                'kind' => 'schedule',
                'source_room_id' => (int) $schedule->room_id,
                'department_id' => $schedule->department_id ? (int) $schedule->department_id : null,
                'day' => $this->dayLabel($schedule->day ?: $schedule->day_of_week),
                'time' => trim((string) ($schedule->time ?: ($schedule->start_time . '–' . $schedule->end_time))),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'course_code' => $schedule->course_code ?: $schedule->course,
                'subject' => $schedule->subject,
                'section' => $schedule->section,
                'instructor' => $schedule->instructor,
            ];
        }

        foreach ($overlays as $overlay) {
            $roomId = (int) ($overlay['room_id'] ?? 0);
            if (!$roomId || !$rooms->has($roomId)) continue;
            $entry = array_merge([
                'id' => 'overlay-' . uniqid(),
                'kind' => 'reservation',
                'day' => $this->dayLabel($overlay['day'] ?? null),
                'time' => trim(($overlay['start_time'] ?? '') . '–' . ($overlay['end_time'] ?? '')),
                'start_time' => $this->clock($overlay['start_time'] ?? null),
                'end_time' => $this->clock($overlay['end_time'] ?? null),
                'course_code' => $overlay['label'] ?? 'Room reservation',
                'subject' => $overlay['description'] ?? null,
                'section' => null,
                'instructor' => $overlay['owner'] ?? null,
                'status' => $overlay['status'] ?? null,
            ], $overlay);
            $entry['day'] = $this->dayLabel($entry['day'] ?? null);
            $entry['start_time'] = $this->clock($entry['start_time'] ?? null);
            $entry['end_time'] = $this->clock($entry['end_time'] ?? null);
            $entriesByRoom[$roomId][] = $entry;
        }

        $roomSheets = [];
        foreach ($rooms->sortBy(fn ($room) => mb_strtolower((string) $room->room_name)) as $room) {
            $entries = collect($entriesByRoom[$room->id] ?? [])
                ->sortBy(fn ($entry) => ($entry['day'] ?? '') . ' ' . ($entry['start_time'] ?? '') . ' ' . ($entry['course_code'] ?? ''))
                ->values()->all();
            $roomSheets[] = [
                'id' => (int) $room->id,
                'name' => $room->room_name ?: 'Room',
                'building' => $room->buildingRelation?->name ?? $room->building,
                'department_id' => $room->department_id ? (int) $room->department_id : null,
                'department' => $room->department?->name,
                'entries' => $entries,
            ];
        }

        return [
            'captured_at' => now()->toIso8601String(),
            'focus' => $focus,
            'rooms' => $roomSheets,
        ];
    }

    /** Save the action and the access scope together, after the owning change succeeds. */
    public function record(string $eventType, string $action, array $data = []): AuditLog
    {
        $actor = array_key_exists('actor', $data) ? $data['actor'] : Auth::user();
        $before = $data['before'] ?? null;
        $after = $data['after'] ?? null;
        $snapshotBefore = $data['snapshot_before'] ?? null;
        $snapshotAfter = $data['snapshot_after'] ?? null;
        $roomIds = array_values(array_unique(array_filter(array_map('intval', $data['room_ids'] ?? []))));
        $scheduleIds = array_values(array_unique(array_filter(array_map('intval', $data['schedule_ids'] ?? []))));

        $departmentIds = array_values(array_unique(array_filter(array_map('intval', $data['department_ids'] ?? []))));
        $relatedUsers = array_values(array_unique(array_filter(array_map('intval', $data['related_user_ids'] ?? []))));
        if ($actor) $relatedUsers[] = (int) $actor->id;

        foreach ([$snapshotBefore, $snapshotAfter] as $snapshot) {
            foreach (($snapshot['rooms'] ?? []) as $room) {
                if (!empty($room['department_id'])) $departmentIds[] = (int) $room['department_id'];
            }
        }

        $instructorNames = $scheduleIds
            ? Schedule::query()->whereIn('id', $scheduleIds)->pluck('instructor')->filter()
                ->map(fn ($name) => mb_strtolower(trim((string) $name)))->unique()->values()
            : collect();
        if ($instructorNames->isNotEmpty()) {
            $relatedUsers = array_merge($relatedUsers, User::query()
                ->whereIn('role', ['faculty', 'chair'])
                ->get(['id', 'name'])
                ->filter(fn (User $user) => $instructorNames->contains(mb_strtolower(trim((string) $user->name))))
                ->pluck('id')->map(fn ($id) => (int) $id)->all());
        }

        $roomId = $data['room_id'] ?? ($roomIds[0] ?? null);
        $scheduleId = $data['schedule_id'] ?? ($scheduleIds[0] ?? null);
        $academicTermId = $data['academic_term_id']
            ?? ($snapshotAfter['focus']['academic_term_id'] ?? $snapshotBefore['focus']['academic_term_id'] ?? null);
        if (!$academicTermId && $scheduleIds) {
            $academicTermId = Schedule::whereIn('id', $scheduleIds)->value('academic_term_id');
        }
        if (!$academicTermId && $scheduleId) {
            $academicTermId = Schedule::whereKey($scheduleId)->value('academic_term_id');
        }
        if (!$academicTermId && !empty($data['reservation_id'])) {
            $academicTermId = DB::table('reservations')->where('id', $data['reservation_id'])->value('academic_term_id');
        }
        $academicTerm = $academicTermId ? AcademicTerm::find($academicTermId) : null;
        $academicTermLabel = $data['academic_term_label']
            ?? ($snapshotAfter['focus']['academic_term_label'] ?? $snapshotBefore['focus']['academic_term_label'] ?? null)
            ?? ($academicTerm ? trim(implode(' · ', array_filter([$academicTerm->academic_year, $academicTerm->semester]))) : null);

        return DB::transaction(function () use ($actor, $eventType, $action, $data, $roomId, $scheduleId, $academicTermId, $academicTermLabel, $roomIds, $scheduleIds, $departmentIds, $relatedUsers, $before, $after, $snapshotBefore, $snapshotAfter): AuditLog {
            $log = AuditLog::create([
                'actor_user_id' => $actor?->id,
                'actor_name' => $actor?->name ?? 'System',
                'actor_role' => $actor?->role ?? 'system',
                'event_type' => $eventType,
                'action' => $action,
                'occurred_at' => $data['occurred_at'] ?? now(),
                'room_id' => $roomId,
                'schedule_id' => $scheduleId,
                'reservation_id' => $data['reservation_id'] ?? null,
                'academic_term_id' => $academicTermId,
                'academic_term_label' => $academicTermLabel,
                'department_ids' => array_values(array_unique($departmentIds)),
                'related_user_ids' => array_values(array_unique($relatedUsers)),
                'related_room_ids' => $roomIds,
                'related_schedule_ids' => $scheduleIds,
                'status_before' => $data['status_before'] ?? ($before['status'] ?? null),
                'status_after' => $data['status_after'] ?? ($after['status'] ?? null),
                'reason' => $data['reason'] ?? null,
                'description' => $data['description'] ?? null,
                'before_data' => $before,
                'after_data' => $after,
                'snapshot_before' => $snapshotBefore,
                'snapshot_after' => $snapshotAfter,
            ]);

            $grants = [];
            foreach (array_unique($departmentIds) as $departmentId) {
                $grants[] = ['audit_log_id' => $log->id, 'principal_type' => 'department', 'principal_id' => $departmentId, 'created_at' => now(), 'updated_at' => now()];
            }
            foreach (array_unique($relatedUsers) as $userId) {
                $grants[] = ['audit_log_id' => $log->id, 'principal_type' => 'user', 'principal_id' => $userId, 'created_at' => now(), 'updated_at' => now()];
            }
            if ($grants) AuditLogAccess::insert($grants);

            return $log;
        });
    }

    private function dayLabel($day): ?string
    {
        if (!$day) return null;
        $value = strtoupper(substr(trim((string) $day), 0, 3));
        return in_array($value, ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'], true) ? $value : $value;
    }

    private function clock($value): ?string
    {
        if (!$value) return null;
        try { return \Carbon\Carbon::parse($value)->format('H:i'); }
        catch (\Throwable $exception) { return trim((string) $value); }
    }

    private function scheduleRange($start, $end, $display): array
    {
        if ($start && $end) return [$this->clock($start), $this->clock($end)];
        $value = trim((string) $display);
        if (!preg_match('/(\d{1,2}(?::\d{2})?\s*(?:A\.?M\.?|P\.?M\.?)?)\s*(?:-|–|—|to)\s*(\d{1,2}(?::\d{2})?\s*(?:A\.?M\.?|P\.?M\.?)?)/i', $value, $matches)) {
            return [$this->clock($start), $this->clock($end)];
        }
        $left = preg_replace('/\./', '', strtoupper(trim($matches[1])));
        $right = preg_replace('/\./', '', strtoupper(trim($matches[2])));
        if (!preg_match('/(?:AM|PM)$/', $right) && preg_match('/(?:AM|PM)$/', $left)) {
            $right .= ' ' . substr($left, -2);
        }
        if (!preg_match('/(?:AM|PM)$/', $left) && preg_match('/(?:AM|PM)$/', $right)) {
            $left .= ' ' . substr($right, -2);
        }
        try {
            return [\Carbon\Carbon::parse($left)->format('H:i'), \Carbon\Carbon::parse($right)->format('H:i')];
        } catch (\Throwable $exception) {
            return [$this->clock($start), $this->clock($end)];
        }
    }
}
