<?php

namespace App\Models;

use App\Enums\TeamEventScope;
use App\Enums\TeamEventStatus;
use App\Enums\TeamEventType;
use Illuminate\Database\Eloquent\Model;

class TeamEvent extends Model
{
    public $casts = [
        "type" => TeamEventType::class,
        "status" => TeamEventStatus::class,
        "scope" => TeamEventScope::class,
    ];
    
    protected $guarded = [];


    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function response()
    {
        return $this->hasMany(TeamEventResponse::class);
    }
}
