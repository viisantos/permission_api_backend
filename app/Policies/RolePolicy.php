<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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
        return $user->can('view roles');
    }

    public function create(User $user)
    {
        return $user->can('create roles');
    }

    public function update(User $user)
    {
        return $user->can('update roles');
    }

    public function delete(User $user)
    {
        return $user->can('delete roles');
    }
}
