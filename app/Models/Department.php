<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}