<?php

namespace App\Models;

use App\Enums\CompetitorProfileType;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $guarded = [];

    public function competitorProfiles()
    {
        return $this->hasMany(CompetitorProfile::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function competitors()
    {
        return $this->competitorProfiles()
            ->where("type", CompetitorProfileType::Student)
            ->orWhere("type", CompetitorProfileType::SubstituteStudent);
    }

    public function students()
    {
        return $this->competitorProfiles()->where(
            "type",
            CompetitorProfileType::Student
        );
    }

    public function substitutes()
    {
        return $this->competitorProfiles()->where(
            "type",
            CompetitorProfileType::SubstituteStudent
        );
    }

    public function teachers()
    {
        return $this->competitorProfiles()->where(
            "type",
            CompetitorProfileType::Teacher
        );
    }
}