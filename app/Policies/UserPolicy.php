<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user)
    {
        return $user->can('view users');
    }

    public function create(User $user)
    {
        return $user->can('create users');
    }

    public function update(User $user)
    {
        return $user->can('update users');
    }

    public function delete(User $user)
    {
        return $user->can('delete users');
    }

}
