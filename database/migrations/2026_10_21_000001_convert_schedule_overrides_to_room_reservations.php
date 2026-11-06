<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedule_overrides', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->after('schedule_id')->constrained('rooms')->nullOnDelete();
            $table->time('start_time')->nullable()->after('override_date');
            $table->time('end_time')->nullable()->after('start_time');
            $table->foreignId('schedule_id')->nullable()->change();
            $table->foreignId('faculty_id')->nullable()->change();
        });

        DB::table('schedule_overrides')->orderBy('id')->chunkById(100, function ($overrides) {
            foreach ($overrides as $override) {
                if (!$override->schedule_id) {
                    continue;
                }

                $schedule = DB::table('schedules')->where('id', $override->schedule_id)->first([
                    'room_id',
                    'time',
                ]);

                if ($schedule) {
                    [$startTime, $endTime] = $this->parseScheduleTimes($schedule->time);
                    DB::table('schedule_overrides')->where('id', $override->id)->update([
                        'room_id' => $schedule->room_id,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ]);
                }
            }
        });

        Schema::table('schedule_overrides', function (Blueprint $table) {
            $table->index(['room_id', 'override_date', 'status'], 'schedule_overrides_room_date_status_idx');
        });
    }

    public function down(): void
    {
        DB::table('schedule_overrides')->whereNull('schedule_id')->delete();

        Schema::table('schedule_overrides', function (Blueprint $table) {
            $table->dropIndex('schedule_overrides_room_date_status_idx');
            $table->dropConstrainedForeignId('room_id');
            $table->dropColumn(['start_time', 'end_time']);
            $table->foreignId('schedule_id')->nullable(false)->change();
            $table->foreignId('faculty_id')->nullable(false)->change();
        });
    }

    private function parseScheduleTimes(?string $value): array
    {
        $parts = preg_split('/\s*(?:-|–|—|to)\s*/i', trim((string) $value), 2);
        if (!$parts || count($parts) !== 2) {
            return [null, null];
        }

        $startPeriod = preg_match('/(AM|PM)$/i', trim($parts[0]), $startMatch) ? strtoupper($startMatch[1]) : null;
        $endPeriod = preg_match('/(AM|PM)$/i', trim($parts[1]), $endMatch) ? strtoupper($endMatch[1]) : null;
        if ($startPeriod && !$endPeriod) {
            $parts[1] .= ' ' . $startPeriod;
        } elseif (!$startPeriod && $endPeriod) {
            $parts[0] .= ' ' . $endPeriod;
        }

        return [$this->toTime($parts[0]), $this->toTime($parts[1])];
    }

    private function toTime(string $value): ?string
    {
        if (!preg_match('/^(\d{1,2})(?::(\d{2}))?\s*(AM|PM)?$/i', trim($value), $match)) {
            return null;
        }

        $hour = (int) $match[1];
        $minute = isset($match[2]) && $match[2] !== '' ? (int) $match[2] : 0;
        $period = strtoupper($match[3] ?? '');
        if ($minute > 59) return null;
        if ($period !== '') {
            if ($hour < 1 || $hour > 12) return null;
            if ($period === 'AM' && $hour === 12) $hour = 0;
            if ($period === 'PM' && $hour !== 12) $hour += 12;
        }
        if ($hour > 23) return null;

        return sprintf('%02d:%02d:00', $hour, $minute);
    }
};
