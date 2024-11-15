<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;
use Spiritix\LadaCache\Database\LadaCacheTrait;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class User extends Authenticatable implements FilamentUser, HasName, Auditable, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory,
        Notifiable,
        HasRelationships,
        LadaCacheTrait,
        AuditingAuditable,
        TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];
    protected $appends = ['name'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function getNameAttribute(): string
    {
        return $this->username;
    }

    public function competitorProfile()
    {
        return $this->hasOne(CompetitorProfile::class);
    }

    public function teams()
    {
        return $this->hasManyDeep(Team::class, [
            CompetitorProfile::class,
            'team_competitor_profile',
        ]);
    }

    public function getFilamentName(): string
    {
        return $this->username;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'competitor' => $this->role == UserRole::Competitor,
            'organizer' => $this->role == UserRole::Organizer,
            'school-manager' => $this->role == UserRole::SchoolManager,
            'teacher' => $this->role == UserRole::Teacher,
            default => true,
        };
    }

    public function canImpersonate(): bool
    {
        return $this->role === UserRole::Organizer;
    }

    public function canBeImpersonated(): bool
    {
        // Let's prevent impersonating other users at our own company
        return $this->role !== UserRole::Organizer;
    }

    public function canAudit(): bool
    {
        return $this->role === UserRole::Organizer;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }
}
