<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Building;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'logo',
        'student_access_code',
        'student_access_code_hash',
    ];

    protected $hidden = [
        'student_access_code',
        'student_access_code_hash',
    ];

    protected function casts(): array
    {
        return ['student_access_code' => 'encrypted'];
    }


    /*
    |--------------------------------------------------------------------------
    | BUILDINGS
    |--------------------------------------------------------------------------
    */

    public function buildings()
    {
        return $this->hasMany(
            Building::class,
            'department_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

    public function users()
    {
        return $this->hasMany(
            User::class,
            'department_id'
        );
    }

      public function rooms()
    {
        return $this->hasMany(
            Room::class,
            'department_id'
        );
    }

        /*
    |--------------------------------------------------------------------------
    | SCHEDULE MERGE REQUESTS
    |--------------------------------------------------------------------------
    */

    public function scheduleMerges()
    {
        return $this->hasMany(
            ScheduleMerge::class,
            'department_id'
        );
    }

}

