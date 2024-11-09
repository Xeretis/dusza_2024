<?php

namespace App\Models;

use Database\Factories\SchoolFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends BaseModel
{
    /** @use HasFactory<SchoolFactory> */
    use HasFactory;

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
