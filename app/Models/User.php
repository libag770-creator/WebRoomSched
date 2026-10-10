<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

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

        return Storage::disk('public')->url($path);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function facultySubjects()
    {
        return $this->hasMany(FacultySubject::class, 'faculty_id', 'id');
    }
}
