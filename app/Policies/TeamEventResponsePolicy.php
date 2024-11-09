<?php

namespace App\Policies;

use App\Models\TeamEventResponse;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

//TODO: Do this
class TeamEventResponsePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TeamEventResponse $teamEventResponse): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, TeamEventResponse $teamEventResponse): bool
    {
        return true;
    }

    public function delete(User $user, TeamEventResponse $teamEventResponse): bool
    {
        return true;
    }

    public function restore(User $user, TeamEventResponse $teamEventResponse): bool
    {
        return true;
    }

    public function forceDelete(User $user, TeamEventResponse $teamEventResponse): bool
    {
        return true;
    }
}
