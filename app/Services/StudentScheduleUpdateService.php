<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\StudentScheduleUpdate;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class StudentScheduleUpdateService
{
    /**
     * Create one public, cohort-scoped update for the owner of a schedule
     * and each approved program sharing that schedule.
     */
    public function recordForSchedule(
        Schedule $schedule,
        string $eventType,
        string $title,
        string $message,
        array $payload = [],
        ?CarbonInterface $effectiveAt = null,
        ?CarbonInterface $expiresAt = null
    ): void {
        $schedule->loadMissing([
            'merges' => function ($query) {
                $query->where('status', 'approved')->with([
                    'subject.program',
                    'facultySubject.subjectCatalog.program',
                ]);
            },
        ]);

        $audiences = [[
            'department_id' => (int) $schedule->department_id,
            'program' => trim((string) $schedule->program),
            'year_level' => trim((string) $schedule->year_level),
        ]];

        foreach ($schedule->merges as $merge) {
            $subject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
            $program = $subject?->program?->name ?? $merge->facultySubject?->program;
            $yearLevel = $subject?->year_level ?? $merge->facultySubject?->year_level;

            $audiences[] = [
                'department_id' => (int) $merge->department_id,
                'program' => trim((string) $program),
                'year_level' => trim((string) $yearLevel),
            ];
        }

        $basePayload = array_merge([
            'schedule_id' => (int) $schedule->id,
            'course_code' => $schedule->course_code,
            'subject' => $schedule->subject,
            'day' => $schedule->day,
            'time' => $schedule->time,
            'room' => $this->roomNameFor($schedule),
            'instructor' => $schedule->instructor,
        ], $payload);

        $uniqueAudiences = collect($audiences)
            ->filter(fn (array $audience) => $audience['department_id'] > 0
                && $audience['program'] !== ''
                && $audience['year_level'] !== '')
            ->unique(fn (array $audience) => implode('|', [
                $audience['department_id'],
                mb_strtolower($audience['program']),
                mb_strtolower($audience['year_level']),
            ]));

        foreach ($uniqueAudiences as $audience) {
            $audienceMessage = $message;
            $audiencePayload = $basePayload;
            $isPrimaryAudience = $audience['department_id'] === (int) $schedule->department_id
                && mb_strtolower($audience['program']) === mb_strtolower(trim((string) $schedule->program))
                && mb_strtolower($audience['year_level']) === mb_strtolower(trim((string) $schedule->year_level));

            if (!$isPrimaryAudience) {
                $mergeForAudience = $schedule->merges->first(function ($merge) use ($audience) {
                    $subject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
                    $program = $subject?->program?->name ?? $merge->facultySubject?->program;
                    $yearLevel = $subject?->year_level ?? $merge->facultySubject?->year_level;
                    return (int) $merge->department_id === $audience['department_id']
                        && mb_strtolower(trim((string) $program)) === mb_strtolower($audience['program'])
                        && mb_strtolower(trim((string) $yearLevel)) === mb_strtolower($audience['year_level']);
                });

                $mergedSubject = $mergeForAudience?->subject ?? $mergeForAudience?->facultySubject?->subjectCatalog;
                if ($mergeForAudience) {
                    $mergedCode = $mergedSubject?->course_code ?? $mergeForAudience->facultySubject?->course_code;
                    $mergedTitle = $mergedSubject?->course_title ?? $mergeForAudience->facultySubject?->subject;
                    if ($mergedCode) {
                        if (!empty($schedule->course_code)) $audienceMessage = str_replace((string) $schedule->course_code, (string) $mergedCode, $audienceMessage);
                        $audiencePayload['course_code'] = $mergedCode;
                    }
                    if ($mergedTitle) {
                        if (!empty($schedule->subject)) $audienceMessage = str_replace((string) $schedule->subject, (string) $mergedTitle, $audienceMessage);
                        $audiencePayload['subject'] = $mergedTitle;
                    }
                }
            }

            $this->recordForAudience(
                $schedule,
                $audience['department_id'],
                $audience['program'],
                $audience['year_level'],
                $eventType,
                $title,
                $audienceMessage,
                $audiencePayload,
                $effectiveAt,
                $expiresAt
            );
        }
    }

    public function recordForAudience(
        Schedule $schedule,
        int $departmentId,
        string $program,
        string $yearLevel,
        string $eventType,
        string $title,
        string $message,
        array $payload = [],
        ?CarbonInterface $effectiveAt = null,
        ?CarbonInterface $expiresAt = null
    ): void {
        $program = trim($program);
        $yearLevel = trim($yearLevel);
        if ($departmentId <= 0 || $program === '' || $yearLevel === '') return;

        StudentScheduleUpdate::create([
            'academic_term_id' => (int) $schedule->academic_term_id,
            'department_id' => $departmentId,
            'program' => $program,
            'year_level' => $yearLevel,
            'schedule_id' => (int) $schedule->id,
            'event_type' => $eventType,
            'title' => $title,
            'message' => $message,
            'payload' => array_merge([
                'schedule_id' => (int) $schedule->id,
                'course_code' => $schedule->course_code,
                'subject' => $schedule->subject,
                'day' => $schedule->day,
                'time' => $schedule->time,
                'room' => $this->roomNameFor($schedule),
                'instructor' => $schedule->instructor,
            ], $payload),
            'effective_at' => $effectiveAt,
            'expires_at' => $expiresAt,
            'created_by' => auth()->id(),
        ]);
    }

    public function roomNameFor(Schedule $schedule): ?string
    {
        if ($schedule->relationLoaded('room')) {
            $relation = $schedule->getRelation('room');
            if (is_object($relation)) {
                return $relation->room_name ?? $relation->name ?? $relation->code ?? null;
            }
        }

        $legacyRoom = $schedule->getAttributes()['room'] ?? null;
        if (is_string($legacyRoom) || is_numeric($legacyRoom)) {
            return trim((string) $legacyRoom);
        }

        if ($schedule->room_id) {
            return $schedule->room()->value('room_name');
        }

        return null;
    }

    public function overlapsScheduledMeeting(Schedule $schedule, string $date, string $startTime, string $endTime): bool
    {
        try {
            if (strtoupper(substr(trim((string) $schedule->day), 0, 3)) !== strtoupper(Carbon::parse($date)->format('D'))) {
                return false;
            }

            $classRange = $this->timeRange((string) $schedule->time, $date);
            $requestedRange = $this->timeRange($startTime . ' - ' . $endTime, $date);
            return $classRange && $requestedRange
                && $classRange[0] < $requestedRange[1]
                && $classRange[1] > $requestedRange[0];
        } catch (\Throwable $exception) {
            return false;
        }
    }

    private function timeRange(string $time, string $date): ?array
    {
        $parts = preg_split('/\s*(?:-|–|—|to)\s*/i', trim($time), 2);
        if (count($parts) !== 2) return null;

        $startPeriod = preg_match('/(AM|PM)$/i', trim($parts[0]), $startMatch) ? strtoupper($startMatch[1]) : null;
        $endPeriod = preg_match('/(AM|PM)$/i', trim($parts[1]), $endMatch) ? strtoupper($endMatch[1]) : null;
        if ($startPeriod && !$endPeriod) $parts[1] .= ' ' . $startPeriod;
        elseif (!$startPeriod && $endPeriod) $parts[0] .= ' ' . $endPeriod;

        $start = Carbon::parse($date . ' ' . trim($parts[0]));
        $end = Carbon::parse($date . ' ' . trim($parts[1]));
        return $end->greaterThan($start)
            ? [$start->hour * 60 + $start->minute, $end->hour * 60 + $end->minute]
            : null;
    }
}
