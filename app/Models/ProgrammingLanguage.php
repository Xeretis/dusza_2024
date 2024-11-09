<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgrammingLanguage extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
