<?php

namespace App\Models;

class School extends BaseModel
{
    protected $guarded = [];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
