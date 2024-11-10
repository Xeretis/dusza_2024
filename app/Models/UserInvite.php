<?php

namespace App\Models;

use App\Enums\UserRole;

class UserInvite extends BaseModel
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
            'expires_at' => 'datetime',
        ];
    }

    public $with = ['competitorProfile'];

    public function competitorProfile()
    {
        return $this->belongsTo(CompetitorProfile::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
