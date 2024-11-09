<?php

namespace App\Models;

use App\Enums\TeamEventResponseStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamEventResponse extends Model
{
    protected $guarded = [];

    public function event(): BelongsTo
    {
        return $this->belongsTo(TeamEvent::class);
    }

    protected function casts(): array
    {
        return [
            'changes' => 'array',
            'status' => TeamEventResponseStatus::class
        ];
    }
}
