<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends BaseModel
{
    /* @use HasFactory<CategoryFactory> */
    use HasFactory;
    
    protected $guarded = [];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
