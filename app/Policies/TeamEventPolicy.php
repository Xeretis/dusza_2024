<?php

namespace App\Policies;

use App\Models\TeamEvent;
use App\Models\User;

class TeamEventPolicy
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
    public function view(User $user, TeamEvent $teamEvent): bool
    {
        return true;
        //TODO: Fix N+1 problem
//        return $user
//            ->teams()
//            ->whereHas("teamEvents", function ($query) use ($teamEvent) {
//                $query->where("id", $teamEvent->id);
//            })
//            ->exists();
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
    public function update(User $user, TeamEvent $teamEvent): bool
    {
        return true;
        //TODO: Fix N+1 problem
//        return $user
//                ->teams()
//                ->whereHas("teamEvents", function ($query) use ($teamEvent) {
//                    $query->where("id", $teamEvent->id);
//                })
//                ->exists() ||
//            $user->role === UserRole::Organizer ||
//            ($user->role === UserRole::SchoolManager &&
//                $teamEvent->team->school_id === $user->school_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TeamEvent $teamEvent): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TeamEvent $teamEvent): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TeamEvent $teamEvent): bool
    {
        return false;
    }
}
