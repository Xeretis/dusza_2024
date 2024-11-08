<?php

namespace App\Policies;

use App\Enums\CompetitorProfileType;
use App\Enums\UserRole;
use App\Models\CompetitorProfile;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompetitorProfilePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CompetitorProfile $competitorProfile): bool
    {
        return $user->competitorProfile?->id === $competitorProfile->id ||
            $user->role === UserRole::Organizer ||
            $competitorProfile
                ->teams()
                ->whereHas("competitorProfiles", function ($query) use ($user) {
                    $query->where(
                        "competitor_profiles.id",
                        $user->competitorProfile->id
                    );
                })
                ->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(
        User $user,
        CompetitorProfile $competitorProfile
    ): bool {
        return $user->competitorProfile?->id === $competitorProfile->id ||
            $user->role === UserRole::Organizer ||
            ($competitorProfile->type !== CompetitorProfileType::Teacher &&
                $competitorProfile
                    ->teams()
                    ->whereHas("competitorProfiles", function ($query) use (
                        $user
                    ) {
                        $query->where(
                            "competitor_profiles.id",
                            $user->competitorProfile->id
                        );
                    })
                    ->exists());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(
        User $user,
        CompetitorProfile $competitorProfile
    ): bool {
        return $user->role === UserRole::Organizer;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(
        User $user,
        CompetitorProfile $competitorProfile
    ): bool {
        return $user->role === UserRole::Organizer;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(
        User $user,
        CompetitorProfile $competitorProfile
    ): bool {
        return $user->role === UserRole::Organizer;
    }
}
