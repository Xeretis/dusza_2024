<?php

namespace App\Models;

use App\Enums\TeamEventScope;
use App\Enums\TeamEventStatus;
use App\Enums\TeamEventType;

class TeamEvent extends BaseModel
{
    public $casts = [
        'type' => TeamEventType::class,
        'status' => TeamEventStatus::class,
        'scope' => TeamEventScope::class,
    ];

    protected $guarded = [];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function response()
    {
        return $this->hasOne(TeamEventResponse::class);
    }
}
