<?php

namespace App\Models;

use App\Enums\CompetitorProfileType;
use Illuminate\Database\Eloquent\Model;

class CompetitorProfile extends Model
{
    protected $guarded = [];

    public $casts = [
        "type" => CompetitorProfileType::class,
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isStudent(): bool
    {
        return $this->type === CompetitorProfileType::Student;
    }

    public function isTeacher(): bool
    {
        return $this->type === CompetitorProfileType::Teacher;
    }

    public function isSubstitute(): bool
    {
        return $this->type === CompetitorProfileType::SubstituteStudent;
    }

    public function pushToUser()
    {
        if ($this->user_id === null) {
            return;
        }

        $user = $this->user;
        $user->email = $this->email;
        $user->name = $this->name;
        $user->save();
    }
}
