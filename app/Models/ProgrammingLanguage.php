<?php

namespace App\Models;

class ProgrammingLanguage extends BaseModel
{
    protected $guarded = [];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
