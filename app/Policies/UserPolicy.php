<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class UserPolicy
{
    use HandlesAuthorization;

    public function __construct(
        protected Request $request
    ) {
    }

    public function index(User $user)
    {
        return $user->isAdmin();
    }

    public function softDelete(User $user)
    {
        return $this->request->is('api/users/me') && !$user->isAdmin();
    }

    public function forceDelete(User $user)
    {
        return $user->isAdmin();
    }

    public function itsMe(User $user)
    {
        return $user->isAdmin();
    }
}
