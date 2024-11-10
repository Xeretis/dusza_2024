<?php

namespace App\Models;

use App\Enums\CompetitorProfileType;
use Illuminate\Notifications\Notifiable;

class CompetitorProfile extends BaseModel
{
    use Notifiable;

    public $casts = [
        'type' => CompetitorProfileType::class,
        'school_ids' => 'array',
    ];
    protected $guarded = [];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_competitor_profile');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
