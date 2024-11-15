<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Team;
use App\Models\User;
use App\Settings\CompetitionSettings;

class TeamPolicy
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
    public function view(User $user, Team $team): bool
    {
        return true;
        // TODO: Fix N+1 issue within the code below
        //        return $user->teams()
        //                ->where('teams.id', $team->id)
        //                ->exists() ||
        //            $user->role === UserRole::Organizer ||
        //            ($user->role === UserRole::SchoolManager &&
        //                $team->school_id === $user->school_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::Organizer ||
            (app(
                CompetitionSettings::class
            )->registration_deadline->isFuture() &&
                is_null(
                    app(CompetitionSettings::class)->registration_cancelled_at
                ));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Team $team): bool
    {
        return true;
        // TODO: Fix N+1 issue within the code below
        //        return $user
        //                ->teams()
        //                ->where("teams.id", $team->id)
        //                ->exists() ||
        //            $user->role === UserRole::Organizer ||
        //            ($user->role === UserRole::SchoolManager &&
        //                $team->school->id === $user->school->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Team $team): bool
    {
        return $user->role === UserRole::Organizer;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Team $team): bool
    {
        return $user->role === UserRole::Organizer;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Team $team): bool
    {
        return $user->role === UserRole::Organizer;
    }
}
