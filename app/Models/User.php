<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected static function booted(): void
    {
        static::updated(function (User $user): void {
            if (!$user->wasChanged('name')) {
                return;
            }

            DB::table('schedules')
                ->where(function ($query) use ($user): void {
                    $query->where('faculty_id', $user->getKey())
                        ->orWhere(function ($legacySchedules) use ($user): void {
                            $legacySchedules->whereNull('faculty_id')
                                ->whereIn('faculty_subject_id', function ($subjects) use ($user): void {
                                    $subjects->select('id')
                                        ->from('faculty_subjects')
                                        ->where('faculty_id', $user->getKey());
                                });
                        });
                })
                ->update(['instructor' => $user->name]);
        });
    }

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'department_id',
        'profile_photo_path',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's profile photo URL.
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        $path = $this->profile_photo_path;

        if (empty($path)) {
            return null;
        }

        // Support both "profile-photos/photo.jpg"
        // and older paths containing "storage/".
        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        // Only return a URL if the image exists.
        if (!Storage::disk('public')->exists($path)) {
            return null;
        }

        return route('profile.photo.show', $this) . '?v=' . rawurlencode($path);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function facultySubjects()
    {
        return $this->hasMany(FacultySubject::class, 'faculty_id', 'id');
    }

    /** Treat legacy accounts with no saved status as active. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function (Builder $active) {
            $active->where('status', 'active')->orWhereNull('status');
        });
    }

    public function isActive(): bool
    {
        return strtolower(trim((string) ($this->status ?? 'active'))) === 'active';
    }
}
