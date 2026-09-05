<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    public function buildings()
    {
        return $this->hasMany(
            Building::class,
            'department_id'
        );
    }

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

}