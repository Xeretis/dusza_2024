<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ["name", "email", "password", "username"];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["password", "remember_token"];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
            "role" => UserRole::class,
        ];
    }

    public function school()
    {
        if ($this->role === UserRole::SchoolManager) {
            return $this->belongsTo(School::class);
        }
        return $this->competitorProfile()->school();
    }

    public function competitorProfile()
    {
        return $this->hasMany(CompetitorProfile::class);
    }

    public function teams()
    {
        return $this->hasManyThrough(Team::class, CompetitorProfile::class);
    }
}
