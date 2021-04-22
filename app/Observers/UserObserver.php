<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

/**
 * Class UserObserver
 * @package App\Observers
 */
class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param User $user
     * @return void
     */
    public function creating(User $user)
    {
        $user->username = Str::lower($user->username);
    }
}
