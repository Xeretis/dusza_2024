<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\TeamEvent;
use App\Models\User;
use Illuminate\Auth\Access\Response;

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
        return $user
            ->teams()
            ->whereHas("events", function ($query) use ($teamEvent) {
                $query->where("id", $teamEvent->id);
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
    public function update(User $user, TeamEvent $teamEvent): bool
    {
        return $user
            ->teams()
            ->whereHas("events", function ($query) use ($teamEvent) {
                $query->where("id", $teamEvent->id);
            })
            ->exists() ||
            $user->role === UserRole::Organizer ||
            ($user->role === UserRole::SchoolManager &&
                $teamEvent->team->school->id === $user->school->id);
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
