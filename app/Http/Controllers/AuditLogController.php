<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\AcademicTerm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\AuditLogRecorder;
use Carbon\Carbon;

class AuditLogController extends Controller
{
    private const TYPES = ['override', 'room_swap', 'reservation'];

    public function index(Request $request)
    {
        $user = $this->authorizedUser();
        $type = $request->query('type', 'override');
        abort_unless(in_array($type, self::TYPES, true), 404);

        $query = $this->visibleLogs($user)->where('event_type', $type);
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'room' => ['nullable', 'string', 'max:120'],
            'actor' => ['nullable', 'string', 'max:120'],
            'status' => ['nullable', 'string', 'max:50'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'academic_term_id' => ['nullable', 'integer', 'min:1'],
        ]);

        if (!empty($filters['search'])) {
            $term = '%' . trim($filters['search']) . '%';
            $query->where(function (Builder $q) use ($term) {
                $q->where('description', 'like', $term)
                    ->orWhere('reason', 'like', $term)
                    ->orWhere('action', 'like', $term)
                    ->orWhere('actor_name', 'like', $term)
                    ->orWhereHas('room', fn (Builder $room) => $room->where('room_name', 'like', $term));
            });
        }

        if (!empty($filters['room'])) {
            $roomTerm = '%' . trim($filters['room']) . '%';
            $query->where(function (Builder $q) use ($roomTerm) {
                $q->whereHas('room', fn (Builder $room) => $room->where('room_name', 'like', $roomTerm))
                    ->orWhere('snapshot_after', 'like', $roomTerm)
                    ->orWhere('snapshot_before', 'like', $roomTerm);
            });
        }

        if (!empty($filters['actor'])) {
            $actorTerm = '%' . trim($filters['actor']) . '%';
            $query->where(function (Builder $q) use ($actorTerm) {
                $q->where('actor_name', 'like', $actorTerm)->orWhere('actor_role', 'like', $actorTerm);
            });
        }

        if (!empty($filters['status'])) {
            $query->where(function (Builder $q) use ($filters) {
                $q->where('status_after', $filters['status'])->orWhere('status_before', $filters['status']);
            });
        }
        if (!empty($filters['from'])) $query->whereDate('occurred_at', '>=', $filters['from']);
        if (!empty($filters['to'])) $query->whereDate('occurred_at', '<=', $filters['to']);
        if (!empty($filters['academic_term_id'])) $query->where('academic_term_id', $filters['academic_term_id']);

        $logs = $query->with(['room', 'schedule'])->latest('occurred_at')->paginate(20)->withQueryString();
        $statusOptions = $this->visibleLogs($user)->where('event_type', $type)
            ->whereNotNull('status_after')->orderBy('status_after')->distinct()->pluck('status_after');
        $academicTermRows = $this->visibleLogs($user)->where('event_type', $type)
            ->whereNotNull('academic_term_id')
            ->select('academic_term_id', 'academic_term_label')->distinct()
            ->orderBy('academic_term_label')->get()
            ->groupBy('academic_term_id');
        $termRecords = AcademicTerm::whereIn('id', $academicTermRows->keys())->get()->keyBy('id');
        $academicTermOptions = $academicTermRows->map(function ($rows, $id) use ($termRecords) {
            $label = $rows->pluck('academic_term_label')->filter()->first();
            $term = $termRecords->get((int) $id);
            $label = $label ?: ($term ? trim(implode(' · ', array_filter([$term->academic_year, $term->semester]))) : 'Term #' . $id);
            return ['id' => (int) $id, 'label' => $label];
        })->values();

        return view('audit-logs.index', compact('logs', 'type', 'filters', 'statusOptions', 'academicTermOptions'));
    }

    public function show(AuditLog $auditLog)
    {
        $user = $this->authorizedUser();
        abort_unless($this->visibleLogs($user)->whereKey($auditLog->id)->exists(), 404);
        $auditLog->load(['room.buildingRelation', 'room.department', 'schedule', 'reservation']);

        $snapshotTermId = $auditLog->snapshot_after['focus']['academic_term_id']
            ?? $auditLog->snapshot_before['focus']['academic_term_id']
            ?? null;
        $academicTermId = $auditLog->academic_term_id
            ?? $snapshotTermId
            ?? $auditLog->after_data['academic_term_id']
            ?? $auditLog->before_data['academic_term_id']
            ?? $auditLog->schedule?->academic_term_id;
        $academicTerm = $auditLog->academicTerm ?: ($academicTermId ? AcademicTerm::find($academicTermId) : null);
        $academicTermLabel = $auditLog->academic_term_label
            ?: ($auditLog->snapshot_after['focus']['academic_term_label'] ?? $auditLog->snapshot_before['focus']['academic_term_label'] ?? null)
            ?: ($academicTerm ? trim(implode(' · ', array_filter([$academicTerm->academic_year, $academicTerm->semester]))) : null);

        // Older migrated records predate immutable snapshots. Reconstruct a
        // labelled fallback from saved action details and current room schedules.
        $snapshotReconstructed = false;
        if (in_array($auditLog->event_type, ['override', 'room_swap', 'reservation'], true)
            && (empty($auditLog->snapshot_before['rooms']) || empty($auditLog->snapshot_after['rooms']))) {
            try {
                $beforeState = $auditLog->before_data ?? [];
                $afterState = $auditLog->after_data ?? [];
                $normalizeState = static function (array $data): array {
                    $nested = is_array($data['request'] ?? null) ? $data['request'] : [];
                    return array_merge($nested, $data);
                };
                $beforeState = $normalizeState($beforeState);
                $afterState = $normalizeState($afterState);
                $state = $afterState ?: $beforeState;
                $scheduleIds = array_values(array_unique(array_filter(array_map('intval', array_merge(
                    [$auditLog->schedule_id, $state['schedule_id'] ?? null, $state['requester_schedule_id'] ?? null, $state['target_schedule_id'] ?? null],
                    $auditLog->related_schedule_ids ?? []
                )))));
                $roomIds = array_values(array_unique(array_filter(array_map('intval', array_merge(
                    [$auditLog->room_id, $state['room_id'] ?? null, $state['requester_room_id'] ?? null, $state['target_room_id'] ?? null],
                    $auditLog->related_room_ids ?? []
                )))));
                $schedules = $scheduleIds
                    ? \App\Models\Schedule::whereIn('id', $scheduleIds)->get()->keyBy('id')
                    : collect();
                $dateValue = $state['override_date'] ?? $state['date'] ?? $state['swap_date'] ?? null;
                $date = $dateValue ? Carbon::parse($dateValue) : null;
                $day = $date?->format('D') ?: ($state['day'] ?? $schedules->first()?->day);
                $startTime = $state['start_time'] ?? $schedules->first()?->start_time;
                $endTime = $state['end_time'] ?? $schedules->first()?->end_time;
                if ((!$startTime || !$endTime) && preg_match('/^\s*(.+?)\s*(?:-|–|—|to)\s*(.+?)\s*$/i', (string) ($schedules->first()?->time ?? ''), $timeParts)) {
                    $startTime ??= trim($timeParts[1]);
                    $endTime ??= trim($timeParts[2]);
                }
                if ($roomIds && $day && $startTime && $endTime) {
                    $focus = [
                        'date' => $date?->format('Y-m-d'),
                        'day' => strtoupper(substr((string) $day, 0, 3)),
                        'start_time' => Carbon::parse($startTime)->format('H:i'),
                        'end_time' => Carbon::parse($endTime)->format('H:i'),
                        'schedule_ids' => $scheduleIds,
                    ];
                    if ($auditLog->event_type === 'room_swap' && !$scheduleIds) {
                        $candidates = \App\Models\Schedule::whereIn('room_id', $roomIds)->get()
                            ->filter(function (\App\Models\Schedule $candidate) use ($focus) {
                                if (strtoupper(substr((string) ($candidate->day ?: $candidate->day_of_week), 0, 3)) !== $focus['day']) return false;
                                if (!preg_match('/^\s*(.+?)\s*(?:-|–|—|\bto\b)\s*(.+?)\s*$/i', (string) $candidate->time, $parts)) return false;
                                try {
                                    $left = trim($parts[1]);
                                    $right = trim($parts[2]);
                                    if (!preg_match('/(?:AM|PM)$/i', $left) && preg_match('/(AM|PM)$/i', $right, $period)) $left .= ' ' . $period[1];
                                    if (!preg_match('/(?:AM|PM)$/i', $right) && preg_match('/(AM|PM)$/i', $left, $period)) $right .= ' ' . $period[1];
                                    $start = Carbon::parse($left)->format('H:i');
                                    $end = Carbon::parse($right)->format('H:i');
                                    return $start < $focus['end_time'] && $end > $focus['start_time'];
                                } catch (\Throwable $exception) {
                                    return false;
                                }
                            })->values();
                        $people = \App\Models\User::whereIn('id', array_filter([
                            $state['requester_id'] ?? null,
                            $state['target_user_id'] ?? null,
                        ]))->get()->keyBy('id');
                        $requesterName = mb_strtolower(trim((string) $people->get((int) ($state['requester_id'] ?? 0))?->name));
                        $targetName = mb_strtolower(trim((string) $people->get((int) ($state['target_user_id'] ?? 0))?->name));
                        $requesterSchedule = $requesterName !== ''
                            ? $candidates->first(fn ($candidate) => mb_strtolower(trim((string) $candidate->instructor)) === $requesterName)
                            : null;
                        $targetSchedule = $targetName !== ''
                            ? $candidates->first(fn ($candidate) => mb_strtolower(trim((string) $candidate->instructor)) === $targetName
                                && (!$requesterSchedule || (int) $candidate->id !== (int) $requesterSchedule->id))
                            : null;
                        $requesterRoom = (int) ($state['requester_room_id'] ?? 0);
                        $targetRoom = (int) ($state['target_room_id'] ?? 0);
                        $requesterSchedule ??= $candidates->first(fn ($candidate) => (int) $candidate->room_id === $requesterRoom);
                        $targetSchedule ??= $candidates->first(fn ($candidate) => (int) $candidate->room_id === $targetRoom
                            && (!$requesterSchedule || (int) $candidate->id !== (int) $requesterSchedule->id));
                        $scheduleIds = collect([$requesterSchedule?->id, $targetSchedule?->id])->filter()->map(fn ($id) => (int) $id)->all();
                        if ($requesterSchedule) $state['requester_schedule_id'] = (int) $requesterSchedule->id;
                        if ($targetSchedule) $state['target_schedule_id'] = (int) $targetSchedule->id;
                        if ($scheduleIds) {
                            $schedules = \App\Models\Schedule::whereIn('id', $scheduleIds)->get()->keyBy('id');
                            $focus['schedule_ids'] = $scheduleIds;
                        }
                    }
                    $termId = $state['academic_term_id'] ?? $schedules->first()?->academic_term_id;
                    if ($termId) $focus['academic_term_id'] = (int) $termId;

                    $action = strtolower((string) $auditLog->action);
                    $statusAfter = strtolower((string) ($afterState['status'] ?? $auditLog->status_after ?? ''));
                    $statusBefore = strtolower((string) ($beforeState['status'] ?? $auditLog->status_before ?? ''));
                    $wasActiveBefore = in_array($statusBefore, ['active', 'approved', 'reserved'], true)
                        || in_array($action, ['cancelled', 'canceled', 'expired', 'deleted'], true);
                    $isActiveAfter = in_array($statusAfter, ['active', 'approved', 'reserved'], true)
                        && !in_array($action, ['cancelled', 'canceled', 'expired', 'deleted', 'rejected'], true);

                    $beforeAssignments = [];
                    $afterAssignments = [];
                    if ($auditLog->event_type === 'room_swap') {
                        $requesterScheduleId = (int) ($state['requester_schedule_id'] ?? ($scheduleIds[0] ?? 0));
                        $targetScheduleId = (int) ($state['target_schedule_id'] ?? ($scheduleIds[1] ?? 0));
                        $requesterRoomId = (int) ($state['requester_room_id'] ?? 0);
                        $targetRoomId = (int) ($state['target_room_id'] ?? 0);
                        if ($requesterScheduleId && $targetScheduleId && $requesterRoomId && $targetRoomId) {
                            $beforeAssignments = [$requesterScheduleId => $requesterRoomId, $targetScheduleId => $targetRoomId];
                            $swappedAssignments = [$requesterScheduleId => $targetRoomId, $targetScheduleId => $requesterRoomId];
                            if ($wasActiveBefore) $beforeAssignments = $swappedAssignments;
                            if ($isActiveAfter) $afterAssignments = $swappedAssignments;
                        }
                    }

                    $overlays = [];
                    if (in_array($auditLog->event_type, ['override', 'reservation'], true)) {
                        $focusRoomId = (int) ($state['room_id'] ?? $auditLog->room_id ?? $roomIds[0]);
                        $overlays[] = [
                            'room_id' => $focusRoomId,
                            'day' => $focus['day'],
                            'start_time' => $focus['start_time'],
                            'end_time' => $focus['end_time'],
                            'label' => $state['occupied_by'] ?? $state['purpose'] ?? 'Room reservation',
                            'description' => $state['reason'] ?? null,
                            'owner' => $auditLog->actor_name,
                            'status' => ucfirst($statusAfter ?: $statusBefore ?: 'recorded'),
                            'highlight' => true,
                        ];
                    }

                    $recorder = app(AuditLogRecorder::class);
                    if (empty($auditLog->snapshot_before['rooms'])) {
                        $auditLog->snapshot_before = $recorder->captureRooms($roomIds, $scheduleIds, $focus, $beforeAssignments, $wasActiveBefore ? $overlays : []);
                    }
                    if (empty($auditLog->snapshot_after['rooms'])) {
                        $auditLog->snapshot_after = $recorder->captureRooms($roomIds, $scheduleIds, $focus, $afterAssignments, $isActiveAfter ? $overlays : []);
                    }
                    $snapshotReconstructed = true;
                }
            } catch (\Throwable $exception) {
                // Keep the existing graceful message when a legacy row lacks usable details.
                report($exception);
            }
        }

        $reservationId = $auditLog->reservation_id
            ?: ($auditLog->after_data['id'] ?? $auditLog->before_data['id'] ?? null);
        $statusHistory = collect();
        if ($auditLog->event_type === 'reservation' && $reservationId) {
            $statusHistory = $this->visibleLogs($user)
                ->where('event_type', 'reservation')
                ->where(function (Builder $query) use ($reservationId) {
                    $query->where('reservation_id', $reservationId)
                        ->orWhereJsonContains('before_data->id', (int) $reservationId)
                        ->orWhereJsonContains('after_data->id', (int) $reservationId);
                })
                ->latest('occurred_at')->get();
        }

        // Legacy snapshots can reveal the term only after their fallback has
        // been reconstructed above; keep that context visible without editing
        // the immutable audit row.
        $academicTermId ??= $auditLog->snapshot_after['focus']['academic_term_id']
            ?? $auditLog->snapshot_before['focus']['academic_term_id']
            ?? null;
        $academicTerm ??= $academicTermId ? AcademicTerm::find($academicTermId) : null;
        $academicTermLabel ??= $auditLog->snapshot_after['focus']['academic_term_label']
            ?? $auditLog->snapshot_before['focus']['academic_term_label']
            ?? ($academicTerm ? trim(implode(' · ', array_filter([$academicTerm->academic_year, $academicTerm->semester]))) : null);

        return view('audit-logs.show', compact('auditLog', 'statusHistory', 'snapshotReconstructed', 'academicTermId', 'academicTermLabel'));
    }

    public function destroy(AuditLog $auditLog)
    {
        $user = $this->authorizedUser();
        $eventType = $auditLog->event_type;

        DB::transaction(function () use ($user, $auditLog) {
            $authorizedLog = $this->visibleLogs($user)
                ->whereKey($auditLog->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            // AuditLog intentionally blocks model deletion to keep records immutable.
            // This explicit, authorized action removes only the selected row;
            // audit_log_access rows are removed by the foreign-key cascade.
            DB::table('audit_logs')->where('id', $authorizedLog->getKey())->delete();
        });

        return redirect()
            ->route('audit-logs.index', ['type' => $eventType])
            ->with('success', 'Audit log deleted permanently.');
    }

    private function authorizedUser()
    {
        abort_unless(Auth::check(), 403);
        $user = Auth::user();
        abort_unless(in_array($user->role, ['admin', 'chair', 'faculty'], true), 403);
        return $user;
    }

    /** Apply server-side record visibility before filters, counts, history, or detail lookup. */
    private function visibleLogs($user): Builder
    {
        $query = AuditLog::query();
        if ($user->role === 'admin') return $query;

        if ($user->role === 'chair') {
            $departmentId = (int) $user->department_id;
            return $query->where(function (Builder $visible) use ($departmentId) {
                $visible->whereHas('accessGrants', fn (Builder $grant) => $grant->where('principal_type', 'department')->where('principal_id', $departmentId))
                    ->orWhere(function (Builder $legacy) use ($departmentId) {
                        $legacy->whereDoesntHave('accessGrants')
                            ->where(function (Builder $scope) use ($departmentId) {
                                $scope->whereJsonContains('department_ids', $departmentId)
                                    ->orWhereHas('room', fn (Builder $room) => $room->where('department_id', $departmentId))
                                    ->orWhereHas('schedule', fn (Builder $schedule) => $schedule->where('department_id', $departmentId));
                            });
                    });
            });
        }

        return $query->where(function (Builder $visible) use ($user) {
            $visible->whereHas('accessGrants', fn (Builder $grant) => $grant->where('principal_type', 'user')->where('principal_id', $user->id))
                ->orWhere(function (Builder $legacy) use ($user) {
                    $legacy->whereDoesntHave('accessGrants')
                        ->where(function (Builder $scope) use ($user) {
                            $scope->where('actor_user_id', $user->id)
                                ->orWhereJsonContains('related_user_ids', (int) $user->id)
                                ->orWhereHas('schedule', function (Builder $schedule) use ($user) {
                                    $schedule->whereRaw('LOWER(TRIM(instructor)) = ?', [mb_strtolower(trim((string) $user->name))]);
                                });
                        });
                });
        });
    }
}
