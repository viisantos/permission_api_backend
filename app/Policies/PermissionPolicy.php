<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
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
        return $user->can('view permissions');
    }

    public function create(User $user)
    {
        return $user->can('create permissions');
    }

    public function update(User $user)
    {
        return $user->can('update permissions');
    }

    public function delete(User $user)
    {
        return $user->can('delete permissions');
    }
}
