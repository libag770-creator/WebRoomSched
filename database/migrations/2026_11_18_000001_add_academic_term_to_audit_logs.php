<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('audit_logs')) return;

        if (!Schema::hasColumn('audit_logs', 'academic_term_id')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                // Keep the historical identifier even if a term is later removed.
                $table->unsignedBigInteger('academic_term_id')->nullable()->after('reservation_id');
                $table->string('academic_term_label', 120)->nullable()->after('academic_term_id');
                $table->index(['academic_term_id', 'occurred_at'], 'audit_term_occurred_idx');
            });
        }

        DB::table('audit_logs')->orderBy('id')->chunkById(200, function ($logs) {
            foreach ($logs as $log) {
                $after = $this->decode($log->snapshot_after ?? null);
                $before = $this->decode($log->snapshot_before ?? null);
                $afterData = $this->decode($log->after_data ?? null);
                $beforeData = $this->decode($log->before_data ?? null);
                $termId = $after['focus']['academic_term_id']
                    ?? $before['focus']['academic_term_id']
                    ?? $afterData['academic_term_id']
                    ?? $beforeData['academic_term_id']
                    ?? null;

                $scheduleIds = array_values(array_unique(array_filter(array_map('intval', array_merge(
                    [(int) ($log->schedule_id ?? 0)],
                    $this->decodeList($log->related_schedule_ids ?? null),
                    $after['focus']['schedule_ids'] ?? [],
                    $before['focus']['schedule_ids'] ?? [],
                    array_filter([
                        $afterData['schedule_id'] ?? null,
                        $beforeData['schedule_id'] ?? null,
                        $afterData['requester_schedule_id'] ?? null,
                        $beforeData['requester_schedule_id'] ?? null,
                        $afterData['target_schedule_id'] ?? null,
                        $beforeData['target_schedule_id'] ?? null,
                    ])
                )))));

                if (!$termId && $scheduleIds) {
                    $termId = DB::table('schedules')->whereIn('id', $scheduleIds)->value('academic_term_id');
                }
                if (!$termId && !empty($log->reservation_id) && Schema::hasColumn('reservations', 'academic_term_id')) {
                    $termId = DB::table('reservations')->where('id', $log->reservation_id)->value('academic_term_id');
                }

                $label = null;
                if ($termId) {
                    $term = DB::table('academic_terms')->where('id', $termId)->first();
                    if ($term) {
                        $label = trim(implode(' · ', array_filter([
                            $term->academic_year ?? null,
                            $term->semester ?? null,
                        ])));
                    }
                }

                if ($termId || $label) {
                    DB::table('audit_logs')->where('id', $log->id)->update([
                        'academic_term_id' => $termId,
                        'academic_term_label' => $label,
                    ]);
                }
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('audit_logs') || !Schema::hasColumn('audit_logs', 'academic_term_id')) return;
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex('audit_term_occurred_idx');
            $table->dropColumn(['academic_term_id', 'academic_term_label']);
        });
    }

    private function decode($value): array
    {
        if (is_array($value)) return $value;
        if (!is_string($value) || $value === '') return [];
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function decodeList($value): array
    {
        if (is_array($value)) return $value;
        if (!is_string($value) || $value === '') return [];
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }
};
