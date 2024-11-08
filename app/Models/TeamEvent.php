<?php

namespace App\Models;

use App\Enums\TeamEventScope;
use App\Enums\TeamEventType;
use Illuminate\Database\Eloquent\Model;

class TeamEvent extends Model
{
    protected $fillable = [
        "team_id",
        "type",
        "status",
        "scope",
        "artifact_url",
        "message",
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public $casts = [
        "type" => TeamEventType::class,
        "status" => TeamEventStatus::class,
        "scope" => TeamEventScope::class,
    ];
}
