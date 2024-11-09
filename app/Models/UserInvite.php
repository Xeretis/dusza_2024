<?php

namespace App\Models;

use App\Enums\UserRole;

class UserInvite extends BaseModel
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            "role" => UserRole::class,
            'expires_at' => 'datetime'
        ];
    }
}
