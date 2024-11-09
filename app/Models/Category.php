<?php

namespace App\Models;

class Category extends BaseModel
{
    protected $guarded = [];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
