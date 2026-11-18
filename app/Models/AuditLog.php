<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LogicException;

class AuditLog extends Model
{
    protected $fillable = [
        'actor_user_id', 'actor_name', 'actor_role', 'event_type', 'action', 'occurred_at',
        'room_id', 'schedule_id', 'reservation_id', 'academic_term_id', 'academic_term_label', 'department_ids', 'related_user_ids',
        'related_room_ids', 'related_schedule_ids', 'status_before', 'status_after', 'reason',
        'description', 'before_data', 'after_data', 'snapshot_before', 'snapshot_after',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'department_ids' => 'array',
        'related_user_ids' => 'array',
        'related_room_ids' => 'array',
        'related_schedule_ids' => 'array',
        'before_data' => 'array',
        'after_data' => 'array',
        'snapshot_before' => 'array',
        'snapshot_after' => 'array',
    ];

    protected static function booted(): void
    {
        static::updating(fn () => throw new LogicException('Audit log records are immutable.'));
        static::deleting(fn () => throw new LogicException('Audit log records are immutable.'));
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class)->withTrashed();
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function accessGrants()
    {
        return $this->hasMany(AuditLogAccess::class);
    }
}
