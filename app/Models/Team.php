<?php

namespace App\Models;

use App\Enums\CompetitorProfileType;
use App\Enums\TeamStatus;
use App\Observers\TeamObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([TeamObserver::class])]
class Team extends BaseModel
{
    public $casts = [
        'status' => TeamStatus::class,
    ];
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function programmingLanguage()
    {
        return $this->belongsTo(ProgrammingLanguage::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teachers()
    {
        return $this->competitorProfiles()->where(
            'type',
            CompetitorProfileType::Teacher
        );
    }

    public function competitorProfiles()
    {
        return $this->belongsToMany(
            CompetitorProfile::class,
            'team_competitor_profile'
        );
    }

    public function events()
    {
        return $this->hasMany(TeamEvent::class);
    }
}
