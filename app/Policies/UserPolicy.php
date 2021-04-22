<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param User $authUser
     * @return mixed
     */
    public function update(User $user, User $authUser)
    {
        return $user->id === $authUser->id ? Response::allow()
            : Response::deny('You do not own this user.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param User $authUser
     * @return mixed
     */
    public function delete(User $user, User $authUser)
    {
        return $user->id === $authUser->id ? Response::allow()
            : Response::deny('You do not own this user.');
    }
}
