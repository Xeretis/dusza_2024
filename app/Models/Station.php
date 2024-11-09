<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends BaseModel
{
    protected $fillable = ["zip", "city", "state"];
}
