<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LogicException;

class AuditLogAccess extends Model
{
    protected $table = 'audit_log_access';

    protected $fillable = ['audit_log_id', 'principal_type', 'principal_id'];

    protected static function booted(): void
    {
        static::updating(fn () => throw new LogicException('Audit log access grants are immutable.'));
        static::deleting(fn () => throw new LogicException('Audit log access grants are immutable.'));
    }

    public function auditLog()
    {
        return $this->belongsTo(AuditLog::class);
    }
}
