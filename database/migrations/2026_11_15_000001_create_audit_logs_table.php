<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $legacyRows = collect();
        if (Schema::hasTable('audit_logs') && !Schema::hasColumn('audit_logs', 'event_type')) {
            if (Schema::hasTable('audit_logs_legacy')) {
                throw new RuntimeException('Both legacy audit_logs and audit_logs_legacy tables exist; resolve the duplicate before migrating.');
            }

            $legacyRows = DB::table('audit_logs')->orderBy('id')->get();
            Schema::rename('audit_logs', 'audit_logs_legacy');
        }

        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('actor_name', 255);
            $table->string('actor_role', 50);
            $table->string('event_type', 30);
            $table->string('action', 60);
            $table->timestamp('occurred_at');
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->nullOnDelete();
            $table->foreignId('reservation_id')->nullable()->constrained('reservations')->nullOnDelete();
            $table->json('department_ids')->nullable();
            $table->json('related_user_ids')->nullable();
            $table->json('related_room_ids')->nullable();
            $table->json('related_schedule_ids')->nullable();
            $table->string('status_before', 50)->nullable();
            $table->string('status_after', 50)->nullable();
            $table->text('reason')->nullable();
            $table->text('description')->nullable();
            $table->json('before_data')->nullable();
            $table->json('after_data')->nullable();
            $table->json('snapshot_before')->nullable();
            $table->json('snapshot_after')->nullable();
            $table->timestamps();

            $table->index(['event_type', 'occurred_at'], 'audit_type_occurred_idx');
            $table->index(['room_id', 'occurred_at'], 'audit_room_occurred_idx');
            $table->index(['schedule_id', 'occurred_at'], 'audit_schedule_occurred_idx');
            $table->index(['reservation_id', 'occurred_at'], 'audit_reservation_occurred_idx');
            $table->index(['actor_user_id', 'occurred_at'], 'audit_actor_occurred_idx');
            $table->index(['status_after', 'occurred_at'], 'audit_status_occurred_idx');
            });
        }

        if (!Schema::hasTable('audit_log_access')) Schema::create('audit_log_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_log_id')->constrained('audit_logs')->cascadeOnDelete();
            $table->string('principal_type', 20);
            $table->unsignedBigInteger('principal_id');
            $table->timestamps();
            $table->unique(['audit_log_id', 'principal_type', 'principal_id'], 'audit_access_grant_unique');
            $table->index(['principal_type', 'principal_id', 'audit_log_id'], 'audit_access_principal_idx');
        });

        foreach ($legacyRows->chunk(100) as $chunk) {
            foreach ($chunk as $legacy) {
                $metadata = json_decode((string) ($legacy->metadata ?? ''), true);
                $metadata = is_array($metadata) ? $metadata : [];
                $legacyEvent = (string) ($legacy->event ?? 'legacy.recorded');
                $eventType = str_starts_with($legacyEvent, 'schedule_override.') ? 'override'
                    : (str_starts_with($legacyEvent, 'room_swap.') ? 'room_swap'
                    : (str_starts_with($legacyEvent, 'reservation.') ? 'reservation' : 'room_swap'));
                $action = str_contains($legacyEvent, '.') ? substr($legacyEvent, strrpos($legacyEvent, '.') + 1) : 'recorded';
                $roomIds = array_values(array_unique(array_filter(array_map('intval', [
                    $metadata['room_id'] ?? null,
                    $metadata['requester_room_id'] ?? null,
                    $metadata['target_room_id'] ?? null,
                ]))));
                $departmentIds = $roomIds
                    ? DB::table('rooms')->whereIn('id', $roomIds)->pluck('department_id')->filter()->map(fn ($id) => (int) $id)->unique()->values()->all()
                    : [];
                $userIds = array_values(array_unique(array_filter(array_map('intval', [
                    $legacy->actor_id ?? null,
                    $metadata['requester_id'] ?? null,
                    $metadata['target_user_id'] ?? null,
                    $metadata['user_id'] ?? null,
                    $metadata['faculty_id'] ?? null,
                ]))));
                $scheduleIds = array_values(array_unique(array_filter(array_map('intval', [
                    $metadata['schedule_id'] ?? null,
                    $metadata['requester_schedule_id'] ?? null,
                    $metadata['target_schedule_id'] ?? null,
                ]))));
                $status = $metadata['status'] ?? null;

                DB::table('audit_logs')->insert([
                    'id' => $legacy->id,
                    'actor_user_id' => $legacy->actor_id,
                    'actor_name' => $legacy->actor_name ?: 'Unknown actor',
                    'actor_role' => $legacy->actor_role ?: 'unknown',
                    'event_type' => $eventType,
                    'action' => $action,
                    'occurred_at' => $legacy->created_at,
                    'room_id' => $roomIds[0] ?? null,
                    'schedule_id' => $scheduleIds[0] ?? null,
                    'reservation_id' => $eventType === 'reservation' ? $legacy->subject_id : null,
                    'department_ids' => json_encode($departmentIds),
                    'related_user_ids' => json_encode($userIds),
                    'related_room_ids' => json_encode($roomIds),
                    'related_schedule_ids' => json_encode($scheduleIds),
                    'status_before' => null,
                    'status_after' => $status,
                    'reason' => $metadata['reason'] ?? $metadata['purpose'] ?? null,
                    'description' => $legacy->summary ?: 'Migrated from the previous audit log.',
                    'before_data' => null,
                    'after_data' => json_encode($metadata),
                    'snapshot_before' => null,
                    'snapshot_after' => null,
                    'created_at' => $legacy->created_at,
                    'updated_at' => $legacy->updated_at,
                ]);

                $grants = [];
                foreach ($departmentIds as $departmentId) {
                    $grants[] = ['audit_log_id' => $legacy->id, 'principal_type' => 'department', 'principal_id' => $departmentId, 'created_at' => now(), 'updated_at' => now()];
                }
                foreach ($userIds as $userId) {
                    $grants[] = ['audit_log_id' => $legacy->id, 'principal_type' => 'user', 'principal_id' => $userId, 'created_at' => now(), 'updated_at' => now()];
                }
                if ($grants) DB::table('audit_log_access')->insert($grants);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('audit_log_access')) Schema::drop('audit_log_access');
        if (Schema::hasTable('audit_logs')) Schema::drop('audit_logs');
        if (Schema::hasTable('audit_logs_legacy')) Schema::rename('audit_logs_legacy', 'audit_logs');
    }
};
