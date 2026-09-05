<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\RoomSwapRequest;
use App\Models\Department;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'department_id',
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
            'department_id' => 'integer',
        ];
    }

    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'department_id'
        );
    }
public function facultySubjects()
{
    return $this->hasMany(
        FacultySubject::class,
        'faculty_id'
    );
}
    public function roomSwapRequests()
    {
        return $this->hasMany(
            RoomSwapRequest::class,
            'requester_id'
        );
    }

    public function receivedRoomSwapRequests()
    {
        return $this->hasMany(
            RoomSwapRequest::class,
            'target_user_id'
        );
    }
}